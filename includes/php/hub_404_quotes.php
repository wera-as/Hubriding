<?php

function HUB_404_quotes()
{

    $content = NULL;

    if (have_rows('jif_404_quotes', 'option')) {

        $quote_ar = [];

        while (have_rows('jif_404_quotes', 'option')) {
            the_row();

            $quote = get_sub_field('jif_404_quote', 'option');

            $quote_ar[] .= $quote;
        }

        shuffle($quote_ar);

        $content .= $quote_ar[0];

        return $content;
    }

    return 'Denne siden finnes ikke';
}
add_shortcode('HUB_404_quotes', 'HUB_404_quotes');