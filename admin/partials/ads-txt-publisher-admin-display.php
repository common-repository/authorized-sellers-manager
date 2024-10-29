<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       Test
 * @since      1.0.0
 *
 * @package    ads-txt-publisher
 * @subpackage ads-txt-publisher/admin/partials
 */
?>
<?php
$file_path = get_home_path() . 'ads.txt';
$ads_file_exists = file_exists($file_path);
?>

<div id="ads-text-publisher" class="ads-text-publisher">
    <div class="ads-text-publisher--hero-block">
        <div class="ads-text-publisher--header-title">Ads.txt Publisher <i class="spinner is-active"
                                                                       id="ads-text-publisher--spinner"></i></div>
        <div class="ads-text-publisher--copyright">by
            <a href="http://brightcom.com" target="_blank">
                <img src="<?php echo $ads_txt_plugin_url ?>/images/brightcom_logo.svg"
                     alt="">
            </a>
        </div>
    </div>

    <?php if (!$ads_file_exists): ?>
        <form method="post" action="<?php echo admin_url('admin-post.php'); ?>" id="handle_create_file">
            <div class="card">
                <h2 class="title">We noticed you don’t have an Ads.txt file set on your website.</h2>
                <p>
                    Would you like to set up one now?
                </p>
                <input type="hidden" name="action" value="handle_create_file">
            </div>
            <br>
            <button type="submit" class="button action button-primary">Set up Ads.txt</button>
        </form>
    <?php else: ?>
        <?php
        $ads_file = file_get_contents($file_path);
        ?>

        <div class="ads-text-publisher--row">
            <div class="ads-text-publisher--column-main">
                <?php if (!get_option('adstxtpublisher-hide-intro-message')) : ?>
                    <div class="ads-text-publisher--card" data-action-name="handle_closing_intro_message"
                         data-action-url="<?php echo admin_url('admin-post.php'); ?>">
                        <h3>Welcome to Ads.txt Publisher! <span
                                    class="ads-text-publisher--card--close dashicons dashicons-no-alt"></span></h3>
                        <p>Here you can easily edit and publish your ads.txt file with one click.
                            Just edit the file below and click “publish”. We will notify you in case there are any
                            errors and help you fix them.
                        </p>

                        <p>
                            Want to learn all about Ads.txt?  Our blog has <a href="http://www.brightcom.com/blog/2017/09/13/publishers-heres-need-know-ads-txt/" target="_blank">all the details</a>.
                        </p>
                    </div>
                <?php endif; ?>
                <div class="ads-text-publisher--edit-form">
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>"
                          id="handle_save_ads_file">
                        <div class="ads-text-publisher--text-output"></div>
                        <textarea name="ads_file" id="ads-file" rows="20" class="ads-text-publisher--textarea"
                                  autocomplete="off" autocorrect="off" autocapitalize="off"
                                  data-enable-grammarly="false"
                                  spellcheck="false"><?php echo $ads_file; ?></textarea>
                        <input type="hidden" name="action" value="handle_save_ads_file">
                        <br>
                        <br>
                        <button type="submit" class="button action button-primary" disabled="disabled">Publish
                        </button>
                    </form>
                </div>

                <div id="ads-text-publisher--messages-container"></div>
            </div>

            <div class="ads-text-publisher--column-sidebar">
                <div class="ads-text-publisher--card">
                    <h2>Ads.txt Publisher by Brightcom</h2>
                    <p> Brightcom has been working with publishers for more than a decade maximizing their yield in a
                        transparent, trusted manner. Read more <a href="http://www.brightcom.com/media-partners/"
                                                                  target="_blank">here</a>.</p>
                    <p><a href="mailto:plugin@brightcom.com ">Contact us</a> with any feedback or question regarding the plugin or the IAB Ads.txt initiative in
                        general.</p>
                </div>
                <div class="ads-text-publisher--invite-form ads-text-publisher--card">
                    <div class="ads-text-publisher--invite-form--description">
                        <h2>Easily invite other Ads.txt editors by email.</h2>
                        <p>Editors will be able to only edit and publish the ads.txt file, and will not have any
                            other WordPress admin access.</p>
                    </div>
                    <form class="" method="post"
                          action="<?php echo admin_url('admin-post.php'); ?>"
                          id="handle_add_user">
                        <label class="ads-text-publisher--label" for="user_email"></label>
                        <input id="user_email" type="email" name="user_email" placeholder="user@website.com">
                        <input type="hidden" name="action" value="handle_add_user">
                        <button type="submit" class="button action button-primary" disabled="disabled">Invite user
                        </button>
                    </form>
                </div>

                <div class="ads-text-publisher--card">
                    <h2>Rate Us!</h2>
                    <p>Please take a moment to rate and review this plugin on the WordPress Plugin Repository.</p>
                    <a href="https://wordpress.org/support/plugin/authorized-sellers-manager/reviews/#new-post" target="_blank" class="button action button-primary">Rate plugin</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
