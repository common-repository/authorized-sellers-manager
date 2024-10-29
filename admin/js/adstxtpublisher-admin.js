(function ($) {
    'use strict';

    function validateAds(value) {
        var lines = value.match(/[^\r\n]+/g) || [];
        var counts = {};

        lines = lines.map(function (line, index) {
            var valid = true;
            var errors = [];

            counts[line] =  (counts[line] || 0) + 1;

            if (counts[line] > 1 && !line.replace(/\s/g,'').match(/^#.*/)) {
                errors.push('Duplicate record');
                valid = false;
            }

            // Remove all whitespaces and comments and split to array by ,
            var elements = line.replace(/\s/g, '')
                .replace(/#.*/, '')
                .split(',');

            var domainRegex = /^([a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,}$/;

            if (elements.length >= 3) {
                if (elements[0].replace(/\p{C}+/u, '').match(domainRegex) === null) {
                    valid = false;
                    errors.push('The domain name is not valid');
                }

                if (elements[0] && elements[0] === 'google.com' && elements[1] && !elements[1].match(/^pub-/)) {
                    valid = false;
                    errors.push('For google.com – must be “pub-“ before the number')
                }

                if (!(elements[2] && elements[2].toLowerCase() === 'direct' || elements[2] && elements[2].toLowerCase() === 'reseller')) {
                    valid = false;
                    errors.push('The line is invalid');
                }

                // If we have comma at the end
                if (elements.length === 4 && !elements[3]) {
                    valid = false;
                    errors.push('The line is invalid');
                }

                if (elements.length > 4) {
                    valid = false;
                    errors.push('The line is invalid');
                }


            } else {
                if (!(elements[0] === '' || elements[0].indexOf('subdomain=') > -1)) {
                    valid = false;
                    errors.push('The line is invalid');
                }
            }

            return {
                valid: valid,
                errors: errors,
                index: index,
                line: line
            };
        })
            .filter(function (lines) {
                return !lines.valid;
            })
        ;

        return {
            valid: !lines.length,
            lines: lines
        }
    }

    $(window).load(function () {
        var $forms = $('#ads-text-publisher form:not([data-ajax="disabled"])');
        var $spinner = $('#ads-text-publisher--spinner');
        var $messagesContainer = $('#ads-text-publisher--messages-container');

        function showMessage(status, message, timer) {
            var $message = $('<div class="ads-text-publisher--message" style="display: none;"> <p></p> </div>');
            var noticeClass = status ? 'notice-success' : 'notice-error';
            $message.find('p').append(message);
            $message.addClass('notice ' + noticeClass);

            var $removeButton = $('<i class="ads-text-publisher--close-message">&times;</i>');

            $removeButton.click(removeMessage);
            $message.append($removeButton);

            $messagesContainer.prepend($message);
            $message.slideDown('fast');

            if (timer) {
                setTimeout(function () {
                    removeMessage();
                }, timer);
            }

            function removeMessage() {
                $message.slideUp('fast', function () {
                    $message.remove();
                })
            }

            return $message;
        }

        function clearMessages() {
            $messagesContainer.empty();
        }

        var validators = {
            'handle_save_ads_file': function ($form) {
                var ads = $form.find('textarea').val();
                var validation = validateAds(ads);
                var valid = validation.valid;
                var $textarea = $('#ads-file');

                if (!validation.valid) {
                    var errorMessagesList = $('<ul></ul>');
                    errorMessagesList.append('<li><h3>Errors:</h3></li>');

                    validation.lines
                        .filter(function (line) {
                            return !line.valid;
                        })
                        .forEach(function (line) {
                            var message = $('<li class="ads-text-publisher--message--error"><b>Line ' + (line.index + 1) + ':</b> ' + line.line + '</li>');
                            var messagesList = $('<ul> </ul>');

                            line.errors.forEach(function (error) {
                                messagesList.append('<li style="color: #dc3232">' + error + '</li>');
                            });

                            message.append(messagesList);

                            message.on('click', function (event) {
                                var ads = $form.find('textarea').val();
                                var indexOfString = ads.search(new RegExp('(^|,)' + line.line.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&") + '(,|$)', 'gm'));

                                setTimeout(function () {
                                    var lineHeight = parseInt($textarea.css('line-height'));
                                    // $textarea.scrollTop(line.index * lineHeight - 100);
                                    $textarea[0].setSelectionRange(indexOfString, indexOfString);
                                }, 1);
                                $textarea.focus();
                            });

                            errorMessagesList.append(message);

                        });

                    showMessage(false, errorMessagesList);

                    $textarea.focus();

                    valid = swal({
                        title: "Attention",
                        text: "Some of the lines have errors. Do you still want to publish?",
                        buttons: {
                            dismiss: {
                                text: 'Cancel',
                                value: null,
                                className: 'button action'
                            },
                            confirm: {
                                text: 'Publish',
                                className: 'button action button-primary'
                            }
                        },
                        className: 'ads-text-publisher--alert',
                        closeOnClickOutside: false,
                    })

                }

                $textarea.highlightWithinTextarea({
                    highlight: validation.lines.filter(function (line) {
                        return !line.valid
                    })
                        .map(function (line) {

                            return new RegExp('^' + line.line.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&") + '$', 'gm');
                        })
                });

                return Promise.resolve(valid);
            }
        };

        var successActions = {
            handle_create_file: function () {
                location.reload();
            }
        };


        $spinner.removeClass('is-active');

        $forms.each(function (index, form) {
            var $form = $(form);
            var id = $form.attr('id');
            var $formElements = $form.find('input, textarea, button');
            $formElements.prop('disabled', false);

            $form.submit(function (event) {
                var valid = true;

                event.preventDefault();

                // Clear all notification messages;
                clearMessages();

                // Do validation form form if validation exists
                if (validators[id]) {
                    return validators[id]($form)
                        .then(function(result) {
                            if (result) {
                                sendRequest();
                            }
                        });
                }

                // Don't send ajax request if is not valid
                if (!valid)
                    return;

                sendRequest();

                function sendRequest() {
                    // Serialize data before form will be disabled (required)
                    var formData = $form.serialize();

                    // Disable form and show spinner when request in progress
                    $formElements.prop('disabled', true);
                    $spinner.addClass('is-active');

                    $.ajax({
                        type: $form.attr('method'),
                        url: $form.attr('action'),
                        data: formData,
                        dataType: 'json'
                    })
                        .complete(function () {
                            // Enable form and hide spinner after request is done
                            $formElements.prop('disabled', false);
                            $spinner.removeClass('is-active');
                        })
                        .done(function (data) {
                            showMessage(data.status, data.message, 6000);

                            if (successActions[id]) {
                                successActions[id]();
                            }
                        })
                        .fail(function (xhr) {
                            var data = xhr.responseJSON;
                            showMessage(data.status, data.message);
                        });
                }


            });
        });

        $('.ads-text-publisher--card--close').each(function (index, closeButton) {
            var $closeButton = $(closeButton);
            var $card = $closeButton.closest('.ads-text-publisher--card');

            $closeButton.on('click', function (event) {
                $card.slideUp();

                if ($card.data('action-name') === 'handle_closing_intro_message') {
                    $.ajax({
                        type: 'POST',
                        url: $card.data('action-url'),
                        dataType: 'json',
                        data: {
                            action: 'handle_closing_intro_message'
                        }
                    })
                        .complete(function (data) {
                            console.log(data);
                        })
                }
            })
        })
    });

})(jQuery);
