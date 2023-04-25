<?php

/**
 * Admin page settings for displaying survey form
 */
add_action('admin_menu', function () {
    add_menu_page(
        __('Product Survey Form and Product Pairs'),
        __('Product Surveys'),
        'manage_options',
        'sbwc-prod-survey',
        'sbwc_prod_survey',
        'dashicons-feedback',
        20
    );
});

/**
 * Render settings page
 */
function sbwc_prod_survey() {
    global $title; ?>

    <div id="sbwc-prod-survey-settings" style="visibility: hidden;">

        <h2><?php echo $title; ?></h2>

        <?php
        // save survey data
        if (isset($_POST['save-survey-settings'])) :

            // retrieve subbed data
            $prod_ids   = $_POST['product-id'];
            $form_ids   = $_POST['survey-form-id'];
            $priorities = $_POST['survey-form-priority'];
            $enable     = $_POST['enable-disable-survey'];

            // build data array to save
            $survey_data = [];

            // loop
            foreach ($prod_ids as $index => $pid) :
                $survey_data[$pid] = [
                    'form_id'  => $form_ids[$index],
                    'priority' => $priorities[$index],
                    'enable'   => $enable[$index]
                ];
            endforeach;

            // save if data set not empty, else delete
            if (!empty($survey_data)) :
                update_option('gf_survey_forms', maybe_serialize($survey_data));
            else :
                delete_option('gf_survey_forms');
            endif;

            // display success message 
        ?>

            <div class="notice notice-success is-dismissible">
                <p><?php _e('Survey settings saved/updated.', 'woocommerce'); ?></p>
            </div>

        <?php
        endif;
        ?>

        <div id="survey-instructions-cont">

            <p><u><b><i>INSTRUCTIONS: </i></b></u></p>

            <p>
                <b>
                    <i>
                        <ol>
                            <li><?php _e('You can add multiple survey forms below.', 'woocommerce'); ?></li>
                            <li><?php _e('If the Product ID is found in an order, the survey form pop-up for that Product ID will display on the order thank you page. <b><u>NOTE: </u> Be sure to add main/parent Product ID in the provided field below, <u>not</u> variation ID!</b>', 'woocommerce'); ?></li>
                            <li><?php _e('To avoid displaying multiple survey form pop-ups at the same time in case of multiple survey Product IDs being present in a single order, use the Priority select field to set priorities for different survey forms. Only the survey form with the highest priority will display on the order thank you page.', 'woocommerce'); ?></li>
                            <li><?php _e('If no display proirity is set as per point (3) above, or priorities are the same between 2 or more surveys, surveys will be prioritized in the order which they are added below.', 'woocommerce'); ?></li>
                            <li><?php _e('A survey pop-up can also be disabled if needed by selecting the appropriate setting using the Enable or disable survey dropdown.', 'woocommrce'); ?></li>
                        </ol>
                    </i>
                </b>
            </p>

        </div>

        <p style="font-size: 14px;"><i><b><u><?php _e('Add survey form / product IDs groups below. Click on Save survey settings to update.', 'woocommerce'); ?></u></b></i></p>

        <!-- forms container -->
        <div id="survey-forms-cont">

            <form action="" method="post">

                <table id="input-set-table" class="wp-list-table fixed striped table-view-list">

                    <thead>
                        <th><?php _e('Survey No', 'woocommerce'); ?></th>
                        <th><?php _e('Survey Data', 'woocommerce'); ?></th>
                        <th><?php _e('Add/Remove', 'woocommerce'); ?></th>
                    </thead>

                    <tbody>

                        <?php

                        // retrieve survey data and loop through to display
                        $survey_data = maybe_unserialize(get_option('gf_survey_forms'));

                        // if survey data
                        if ($survey_data && is_array($survey_data) && !empty($survey_data)) :

                            // $counter
                            $counter = 1;

                            // loop
                            foreach ($survey_data as $pid => $data) : ?>
                                <tr class="input-set-row">

                                    <!-- input set number -->
                                    <td class="input-set-no"><b><?php echo $counter; ?></b></td>

                                    <!-- input set -->
                                    <td class="input-set">

                                        <!-- product ID tied to form -->
                                        <p>
                                            <label for="product-id"><i><b><u><?php _e('Product ID:', 'woocommerce'); ?></u></b></i> </label>
                                        </p>
                                        <p>
                                            <input type="text" name="product-id[]" class="product-id" required value="<?php echo $pid; ?>">
                                        </p>

                                        <span class="help">
                                            <?php _e('The main/parent product ID this survey form is for. Used to display survey form if product ID present in order.', 'woocommerce'); ?>
                                        </span>

                                        <!-- gravity form ID -->
                                        <p>
                                            <label for="survey-form-id"><i><b><u><?php _e('Survey form ID:', 'woocommerce'); ?></u></b></i> </label>
                                        </p>
                                        <p>
                                            <input type="text" name="survey-form-id[]" class="survey-form-id" required value="<?php echo $data['form_id']; ?>">
                                        </p>

                                        <span class="help"><?php _e('The Gravity Forms survey form ID for this product', 'woocommerce'); ?></span>

                                        <!-- priority -->
                                        <p>
                                            <label for="survey-form-priority"><i><b><u><?php _e('Survey form priority (optional):', 'woocommerce'); ?></u></b></i></label>
                                        </p>
                                        <p>
                                            <select name="survey-form-priority[]" class="survey-form-priority" data-priority="<?php echo $data['priority']; ?>">
                                                <option value="">Please select</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </p>

                                        <span class="help"><?php _e('The priority of this survey on the front-end. If multiple survey products are present in an order,<br> the one with the highest priority will be displayed to avoid multiple survey pop-ups from appearing at once.', 'woocommerce'); ?></span>

                                        <!-- enable/disable -->
                                        <p>
                                            <label for="enable-disable-survey"><i><b><u><?php _e('Enable or disable survey?', 'woocommerce'); ?></u></b></i> </label>
                                        </p>

                                        <p>
                                            <select name="enable-disable-survey[]" class="enable-disable-survey" required data-enabled="<?php echo $data['enable']; ?>">
                                                <option value=""><?php _e('Please select', 'woocommere'); ?></option>
                                                <option value="enable"><?php _e('Enable', 'woocommerce'); ?></option>
                                                <option value="disable"><?php _e('Disable', 'woocommerce'); ?></option>
                                            </select>
                                        </p>
                                    </td>

                                    <!-- add/remove input set -->
                                    <td class="add-rem-input-set">
                                        <!-- add -->
                                        <span class="add-survey-input-set button button-primary button-small">+</span>
                                        <!-- remove -->
                                        <span class="rem-survey-input-set button button-secondary button-small">-</span>
                                    </td>
                                </tr>
                            <?php
                                $counter++;
                            endforeach;

                        else : ?>

                            <tr class="input-set-row">

                                <!-- input set number -->
                                <td class="input-set-no"><b>1</b></td>

                                <!-- input set -->
                                <td class="input-set">

                                    <!-- product ID tied to form -->
                                    <p>
                                        <label for="product-id"><i><b><u><?php _e('Product ID:', 'woocommerce'); ?></u></b></i> </label>
                                    </p>
                                    <p>
                                        <input type="text" name="product-id[]" class="product-id" required>
                                    </p>

                                    <span class="help">
                                        <?php _e('The main/parent product ID this survey form is for. Used to display survey form if product ID present in order.', 'woocommerce'); ?>
                                    </span>

                                    <!-- gravity form ID -->
                                    <p>
                                        <label for="survey-form-id"><i><b><u><?php _e('Survey form ID:', 'woocommerce'); ?></u></b></i> </label>
                                    </p>
                                    <p>
                                        <input type="text" name="survey-form-id[]" class="survey-form-id" required>
                                    </p>

                                    <span class="help"><?php _e('The Gravity Forms survey form ID for this product', 'woocommerce'); ?></span>

                                    <!-- priority -->
                                    <p>
                                        <label for="survey-form-priority"><i><b><u><?php _e('Survey form priority (optional):', 'woocommerce'); ?></u></b></i></label>
                                    </p>
                                    <p>
                                        <select name="survey-form-priority[]" class="survey-form-priority">
                                            <option value="">Please select</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                    </p>

                                    <span class="help"><?php _e('The priority of this survey on the front-end. If multiple survey products are present in an order,<br> the one with the highest priority will be displayed to avoid multiple survey pop-ups from appearing at once.', 'woocommerce'); ?></span>

                                    <!-- enable/disable -->
                                    <p>
                                        <label for="enable-disable-survey"><i><b><u><?php _e('Enable or disable survey?', 'woocommerce'); ?></u></b></i> </label>
                                    </p>

                                    <p>
                                        <select name="enable-disable-survey[]" class="enable-disable-survey" required>
                                            <option value=""><?php _e('Please select', 'woocommere'); ?></option>
                                            <option value="enable"><?php _e('Enable', 'woocommerce'); ?></option>
                                            <option value="disable"><?php _e('Disable', 'woocommerce'); ?></option>
                                        </select>
                                    </p>
                                </td>

                                <!-- add/remove input set -->
                                <td class="add-rem-input-set">
                                    <!-- add -->
                                    <span class="add-survey-input-set button button-primary button-small">+</span>
                                    <!-- remove -->
                                    <span class="rem-survey-input-set button button-secondary button-small">-</span>
                                </td>
                            </tr>
                        <?php endif;

                        ?>


                    </tbody>
                </table>

                <!-- save settings -->
                <input type="submit" name="save-survey-settings" id="save-survey-settings" class="button button-large button-primary" value="<?php _e('Save survey settings', 'woocommerce'); ?>">

            </form>

        </div>

    </div>

    <!-- js -->
    <script>
        jQuery(document).ready(function($) {

            // vars
            var to_insert = '<tr class="input-set-row">' + $('.input-set-row').html() + '</td>',
                target = $('#input-set-table > tbody'),
                row_count,
                new_count;

            // add input set to table on click
            $(this).on('click', '.add-survey-input-set', function(e) {
                e.preventDefault();

                // update row count
                row_count = $('.input-set-row').length,
                    new_count = parseInt(row_count) + 1;

                // append html
                target.append(to_insert);

                // set correct row count
                $(':last-child', target).find('td.input-set-no').empty().text(new_count);

            })

            // remove input set on click
            $(this).on('click', '.rem-survey-input-set', function(e) {
                e.preventDefault();

                // update row count
                row_count = $('.input-set-row').length;

                if (row_count > 1) {
                    new_count = parseInt(row_count) - 1;
                }

                // only delete row if count bigger than one
                if (new_count > 1) {
                    $(this).parents('.input-set-row').remove();
                }

            })

            // set dropdown values for previously saved dataset
            $('select.survey-form-priority').each(function() {
                $(this).val($(this).data('priority'));
            });
            $('select.enable-disable-survey').each(function() {
                $(this).val($(this).data('enabled'));
            });

            // set submit button width
            $('#save-survey-settings').width(parseFloat($('.input-set-row').width() - parseFloat(25)));

            // set info box width
            $('#survey-instructions-cont').width(parseFloat($('.input-set-row').width() - parseFloat(25)));

            // show settings cont
            setTimeout(() => {
                $('#sbwc-prod-survey-settings').css('visibility', 'visible');
            }, 500);

        });
    </script>

    <!-- styling -->
    <style>
        div#survey-instructions-cont {
            background: #f6f7f7;
            padding: 5px 15px;
            margin-bottom: 30px;
            width: 50vw;
            box-shadow: 2px 2px 4px lightgrey;
            border: 1px solid #ddd;
            border-radius: 2px;
        }

        div#sbwc-prod-survey-settings>h2 {
            background: white;
            box-shadow: 2px 2px 4px lightgrey;
            padding: 15px 20px;
            margin-top: 0;
            margin-left: -19px;
        }

        #input-set-table>thead>tr {
            background: white;
        }

        #input-set-table>thead>tr>th {
            padding: 10px 15px;
        }

        #input-set-table>tbody>tr>td {
            padding: 10px 15px;
        }

        td.input-set-no {
            text-align: center;
            font-size: 14px;
        }

        td.add-rem-input-set {
            text-align: center;
        }

        td.input-set input,
        td.input-set select {
            width: 360px;
        }

        span.add-survey-input-set.button.button-primary.button-small {
            min-width: 26px;
        }

        span.rem-survey-input-set.button.button-secondary.button-small {
            min-width: 26px;
            border-color: red;
            background: red;
            color: white;
        }

        label {
            padding-left: 5px;
        }

        span.help {
            position: relative;
            font-size: 12px;
            font-style: italic;
            bottom: 11px;
            left: 3px;
        }

        td.input-set-no,
        td.input-set,
        td.add-rem-input-set {
            border-bottom: 2px solid #ddd;
        }

        table#input-set-table>thead>tr>th {
            border-bottom: 2px solid #ddd;
        }

        input#save-survey-settings {
            font-size: 16px;
            position: relative;
            top: 20px;
        }

        .notice.notice-success.is-dismissible {
            left: -14px;
            margin-bottom: 20px;
        }
    </style>
<?php }
