<?php

/**
 * Add survey pop-up to order thank you page
 */
add_action('woocommerce_thankyou', function () {

    // retrieve order
    $order_id = wc_get_order_id_by_order_key($_GET['key']);
    $order = wc_get_order($order_id);

    // retrieve survey data
    $survey_data = maybe_unserialize(get_option('gf_survey_forms'));

    // bail if no $survey_data
    if (!is_array($survey_data) || empty($survey_data)) :
        return;
    endif;

    // retrieve product ids
    $prod_data = $order->get_data();
    $line_items = $prod_data['line_items'];

    // product id array
    $product_ids = [];

    // line items loop
    foreach ($line_items as $item_id => $item_object) :

        // retrieve item data
        $item_data = $item_object->get_data();

        // push product id to $product_ids
        $product_ids[] = $item_data['product_id'];
    endforeach;

    // setup filtered survey products array
    $filtered_survey_prods = [];

    // check if order product ids present in $survey_data
    if (is_array($survey_data)  && !empty($survey_data)) :

        // loop through $product_ids and push relevant product survey data to $filtered_survey_prods
        foreach ($product_ids as $pid) :
            if (key_exists($pid, $survey_data)) :
                $filtered_survey_prods[$pid] = $survey_data[$pid];
            endif;
        endforeach;

    endif;

    // if $filtered_survey_prods empty, bail
    if (empty($filtered_survey_prods)) :
        return;
    endif;

    // remove disabled surveys from $filtered_survey_prods
    foreach ($filtered_survey_prods as $prod_id => $survey_data) :
        if ($survey_data['enable'] === 'disable') :
            unset($filtered_survey_prods[$prod_id]);
        endif;
    endforeach;

    // sort $filtered_survey_prods by priority
    usort($filtered_survey_prods, function ($x, $y) {
        return $x['priority'] <=> $y['priority'];
    });

    // extract final survey form
    $final_survey = $filtered_survey_prods[0];

    // get gf form id
    $gf_form_id = $final_survey['form_id'];

    // setup shorcode
    $gf_sc = "[gravityform id=\"$gf_form_id\" title=\"true\"]";

    // check if GF class exists, retrieve form data; if form data exists, display via shortcode, else bail; else bail
    if (class_exists('GFAPI')) :

        $gf_form_data = GFAPI::get_form($gf_form_id);

        if (is_array($gf_form_data)  && !empty($gf_form_data)) :

            // echo do_shortcode($gf_sc);
?>

            <!-- modal overlay -->
            <div id="gf-survey-form-overlay" style="display: none;"></div>

            <!-- modal container -->
            <div id="gf-survey-form-container" style="display: none;">

                <!-- close/dismiss -->
                <a href="#" class="gf-survey-form-dismiss" title="<?php _e('Cancel', 'woocommerce'); ?>">x</a>

                <!-- survey form -->
                <div id="gf-survey-form-inner-container">
                    <?php echo do_shortcode($gf_sc); ?>
                </div>

            </div>

            <style>
                div#gf-survey-form-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    background: #0000008a;
                    width: 100vw;
                    height: 100vh;
                    z-index: 10000;
                }

                div#gf-survey-form-container {
                    background: white;
                    padding: 20px;
                    border: 1px solid #ddd;
                    width: 50vw;
                    position: absolute;
                    z-index: 10001;
                    border-radius: 5px;
                    top: -14vh;
                    left: 25vw;
                }

                a.gf-survey-form-dismiss {
                    position: absolute;
                    right: -45px;
                    top: 0;
                    width: 30px;
                    height: 30px;
                    background: #ddd;
                    text-align: center;
                    border-radius: 50%;
                    font-size: 16px;
                }

                div#gf-survey-form-inner-container .gchoice {
                    border: 1px solid #ddd;
                    background: #00000094;
                    border-radius: 4px;
                    transition: all 0.2s;
                }

                div#gf-survey-form-inner-container .gchoice:hover {
                    border: #ccc;
                    background: #ccc;
                    transition: all 0.2s;
                }

                div#gf-survey-form-inner-container .gchoice:hover label {
                    color: #666;
                }

                .survey-choice-selected {
                    border: #ccc !important;
                    background: #ccc !important;
                }

                .label-selected {
                    color: #666 !important;
                }

                div#gf-survey-form-inner-container .gchoice>input {
                    display: none;
                }

                .gchoice label {
                    display: block !important;
                    max-width: 100% !important;
                    width: 100% !important;
                    text-align: center;
                    font-weight: 500;
                    color: white;
                    padding-top: 40%;
                }

                div#gf-survey-form-container .gform_confirmation_wrapper {
                    font-size: 16px;
                    text-align: center;
                    font-weight: 500;
                }

                #gf-survey-form-container .gform_button {
                    border-radius: 3px;
                }

                /* 1536px */
                @media screen and (max-width: 1536px) {
                    div#gf-survey-form-container {
                        width: 63vw;
                        left: 18vw;
                    }
                }

                /* 1440px */
                @media screen and (max-width: 1440px) {
                    div#gf-survey-form-container {
                        width: 67vw;
                        left: 15.5vw;
                    }
                }

                /* 1366px */
                @media screen and (max-width: 1366px) {
                    div#gf-survey-form-container {
                        width: 71vw;
                        left: 13.5vw;
                    }
                }

                /* 1280px */
                @media screen and (max-width: 1280px) {
                    div#gf-survey-form-container {
                        width: 72vw;
                    }
                }

                /* 962px */
                @media screen and (max-width: 962px) {
                    div#gf-survey-form-container {
                        width: 94vw;
                        left: 3vw;
                    }

                    a.gf-survey-form-dismiss {
                        right: -15px;
                        top: -15px;
                    }

                    div#gf-survey-form-inner-container .gchoice {
                        margin-bottom: 3px;
                    }
                }

                /* 800px */
                @media screen and (max-width: 800px) {
                    .gchoice label {
                        padding-top: 39%;
                    }
                }

                /* 768px */
                @media screen and (max-width: 768px) {}

                /* 601px */
                @media screen and (max-width: 601px) {}

                /* 414px */
                @media screen and (max-width: 414px) {}

                /* 375px */
                @media screen and (max-width: 375px) {}

                /* 360px */
                @media screen and (max-width: 360px) {}
            </style>

            <script>
                jQuery(document).ready(function($) {

                    // get screen width and set up max opts per row
                    var screen_width = screen.width,
                        max_per_row;

                    if (screen_width >= 962) {
                        max_per_row = 5;
                    } else if (screen_width >= 768 && screen_width < 962) {
                        max_per_row = 4;
                    } else if (screen_width <= 601 && screen_width > 414) {
                        max_per_row = 3;
                    } else {
                        max_per_row = 2;
                    }

                    setTimeout(() => {

                        // show survey popup
                        $('#gf-survey-form-overlay, #gf-survey-form-container').fadeIn(200);

                        // get choice count
                        var choice_count = $('.gchoice').length,
                            width_height,
                            cont_width = $('#gf-survey-form-inner-container').width();

                        // setup choice width height
                        // if (choice_count <= max_per_row) {
                        width_height = cont_width / max_per_row;

                        console.log(width_height);

                        // }

                        // set choices width and height
                        $('.gchoice').width(width_height - 6).find('label').height(width_height - 6).css('max-height', width_height - 6);
                        $('.gchoice').css({
                            'display': 'inline-flex',
                            'position': 'relative'
                        });

                        // choice on click
                        $('.gchoice').each(function(index, element) {
                            $(this).on('click', function() {
                                $('.gchoice').removeClass('survey-choice-selected').find('label').removeClass('label-selected');
                                $(this).addClass('survey-choice-selected').find('label').addClass('label-selected');
                            });
                        });

                        // dismiss form modal
                        $('.gf-survey-form-dismiss, #gf-survey-form-overlay').click(function(e) {
                            e.preventDefault();
                            $('#gf-survey-form-overlay, #gf-survey-form-container').fadeOut(200);
                        });

                    }, 2500);

                });
            </script>

<?php else :
            return;
        endif;

    else :
        return;
    endif;
});
