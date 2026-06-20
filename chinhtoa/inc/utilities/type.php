<?php
if (!defined('ABSPATH')) {
    exit;
}

// ct_get_option_setting() now lives in inc/options/storage.php (native store).

/**
 * Normalize a "category" option value to a comma-separated ID string for queries.
 * Accepts both an array (legacy Unyson multi-select) and a string (native editor
 * text field, e.g. "1" or "1,2"). PHP 8-safe — never implode()s a string.
 *
 * @param mixed $cats
 * @return string
 */
function ct_cats_str($cats)
{
    if (is_array($cats)) {
        return implode(',', $cats);
    }
    return (string) $cats;
}

function vn_to_str($str)
{
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
        'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D' => 'Đ',
        'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
        'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
    );
    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $str = str_replace(' ', '_', $str);
    return $str;
}

function removeInvalidChars($content, $charsArr, $replace)
{
    return str_replace($charsArr, $replace, $content);
}

function isBetweenDates($dateToCheck, $start_date, $end_date)
{
    $start = strtotime($start_date);
    $end = strtotime($end_date);
    $date = strtotime($dateToCheck);

    // Check that user date is between start & end
    return (($date > $start) && ($date < $end));
}


function getCountries()
{
    $countryArr = array(
        "AC" => "🇦🇨 Ascension Island",
        "AD" => "🇦🇩 Andorra",
        "AE" => "🇦🇪 United Arab Emirates",
        "AF" => "🇦🇫 Afghanistan",
        "AG" => "🇦🇬 Antigua & Barbuda",
        "AI" => "🇦🇮 Anguilla",
        "AL" => "🇦🇱 Albania",
        "AM" => "🇦🇲 Armenia",
        "AO" => "🇦🇴 Angola",
        "AQ" => "🇦🇶 Antarctica",
        "AR" => "🇦🇷 Argentina",
        "AS" => "🇦🇸 American Samoa",
        "AT" => "🇦🇹 Austria",
        "AU" => "🇦🇺 Australia",
        "AW" => "🇦🇼 Aruba",
        "AX" => "🇦🇽 Åland Islands",
        "AZ" => "🇦🇿 Azerbaijan",
        "BA" => "🇧🇦 Bosnia & Herzegovina",
        "BB" => "🇧🇧 Barbados",
        "BD" => "🇧🇩 Bangladesh",
        "BE" => "🇧🇪 Belgium",
        "BF" => "🇧🇫 Burkina Faso",
        "BG" => "🇧🇬 Bulgaria",
        "BH" => "🇧🇭 Bahrain",
        "BI" => "🇧🇮 Burundi",
        "BJ" => "🇧🇯 Benin",
        "BL" => "🇧🇱 St. Barthélemy",
        "BM" => "🇧🇲 Bermuda",
        "BN" => "🇧🇳 Brunei",
        "BO" => "🇧🇴 Bolivia",
        "BQ" => "🇧🇶 Caribbean Netherlands",
        "BR" => "🇧🇷 Brazil",
        "BS" => "🇧🇸 Bahamas",
        "BT" => "🇧🇹 Bhutan",
        "BV" => "🇧🇻 Bouvet Island",
        "BW" => "🇧🇼 Botswana",
        "BY" => "🇧🇾 Belarus",
        "BZ" => "🇧🇿 Belize",
        "CA" => "🇨🇦 Canada",
        "CC" => "🇨🇨 Cocos (Keeling) Islands",
        "CD" => "🇨🇩 Congo - Kinshasa",
        "CF" => "🇨🇫 Central African Republic",
        "CG" => "🇨🇬 Congo - Brazzaville",
        "CH" => "🇨🇭 Switzerland",
        "CI" => "🇨🇮 Côte d’Ivoire",
        "CK" => "🇨🇰 Cook Islands",
        "CL" => "🇨🇱 Chile",
        "CM" => "🇨🇲 Cameroon",
        "CN" => "🇨🇳 China",
        "CO" => "🇨🇴 Colombia",
        "CP" => "🇨🇵 Clipperton Island",
        "CR" => "🇨🇷 Costa Rica",
        "CU" => "🇨🇺 Cuba",
        "CV" => "🇨🇻 Cape Verde",
        "CW" => "🇨🇼 Curaçao",
        "CX" => "🇨🇽 Christmas Island",
        "CY" => "🇨🇾 Cyprus",
        "CZ" => "🇨🇿 Czechia",
        "DE" => "🇩🇪 Germany",
        "DG" => "🇩🇬 Diego Garcia",
        "DJ" => "🇩🇯 Djibouti",
        "DK" => "🇩🇰 Denmark",
        "DM" => "🇩🇲 Dominica",
        "DO" => "🇩🇴 Dominican Republic",
        "DZ" => "🇩🇿 Algeria",
        "EA" => "🇪🇦 Ceuta & Melilla",
        "EC" => "🇪🇨 Ecuador",
        "EE" => "🇪🇪 Estonia",
        "EG" => "🇪🇬 Egypt",
        "EH" => "🇪🇭 Western Sahara",
        "ER" => "🇪🇷 Eritrea",
        "ES" => "🇪🇸 Spain",
        "ET" => "🇪🇹 Ethiopia",
        "EU" => "🇪🇺 European Union",
        "FI" => "🇫🇮 Finland",
        "FJ" => "🇫🇯 Fiji",
        "FK" => "🇫🇰 Falkland Islands",
        "FM" => "🇫🇲 Micronesia",
        "FO" => "🇫🇴 Faroe Islands",
        "FR" => "🇫🇷 France",
        "GA" => "🇬🇦 Gabon",
        "GB" => "🇬🇧 United Kingdom",
        "GD" => "🇬🇩 Grenada",
        "GE" => "🇬🇪 Georgia",
        "GF" => "🇬🇫 French Guiana",
        "GG" => "🇬🇬 Guernsey",
        "GH" => "🇬🇭 Ghana",
        "GI" => "🇬🇮 Gibraltar",
        "GL" => "🇬🇱 Greenland",
        "GM" => "🇬🇲 Gambia",
        "GN" => "🇬🇳 Guinea",
        "GP" => "🇬🇵 Guadeloupe",
        "GQ" => "🇬🇶 Equatorial Guinea",
        "GR" => "🇬🇷 Greece",
        "GS" => "🇬🇸 South Georgia & South Sandwich Islands",
        "GT" => "🇬🇹 Guatemala",
        "GU" => "🇬🇺 Guam",
        "GW" => "🇬🇼 Guinea-Bissau",
        "GY" => "🇬🇾 Guyana",
        "HK" => "🇭🇰 Hong Kong SAR China",
        "HM" => "🇭🇲 Heard & McDonald Islands",
        "HN" => "🇭🇳 Honduras",
        "HR" => "🇭🇷 Croatia",
        "HT" => "🇭🇹 Haiti",
        "HU" => "🇭🇺 Hungary",
        "IC" => "🇮🇨 Canary Islands",
        "ID" => "🇮🇩 Indonesia",
        "IE" => "🇮🇪 Ireland",
        "IL" => "🇮🇱 Israel",
        "IM" => "🇮🇲 Isle of Man",
        "IN" => "🇮🇳 India",
        "IO" => "🇮🇴 British Indian Ocean Territory",
        "IQ" => "🇮🇶 Iraq",
        "IR" => "🇮🇷 Iran",
        "IS" => "🇮🇸 Iceland",
        "IT" => "🇮🇹 Italy",
        "JE" => "🇯🇪 Jersey",
        "JM" => "🇯🇲 Jamaica",
        "JO" => "🇯🇴 Jordan",
        "JP" => "🇯🇵 Japan",
        "KE" => "🇰🇪 Kenya",
        "KG" => "🇰🇬 Kyrgyzstan",
        "KH" => "🇰🇭 Cambodia",
        "KI" => "🇰🇮 Kiribati",
        "KM" => "🇰🇲 Comoros",
        "KN" => "🇰🇳 St. Kitts & Nevis",
        "KP" => "🇰🇵 North Korea",
        "KR" => "🇰🇷 South Korea",
        "KW" => "🇰🇼 Kuwait",
        "KY" => "🇰🇾 Cayman Islands",
        "KZ" => "🇰🇿 Kazakhstan",
        "LA" => "🇱🇦 Laos",
        "LB" => "🇱🇧 Lebanon",
        "LC" => "🇱🇨 St. Lucia",
        "LI" => "🇱🇮 Liechtenstein",
        "LK" => "🇱🇰 Sri Lanka",
        "LR" => "🇱🇷 Liberia",
        "LS" => "🇱🇸 Lesotho",
        "LT" => "🇱🇹 Lithuania",
        "LU" => "🇱🇺 Luxembourg",
        "LV" => "🇱🇻 Latvia",
        "LY" => "🇱🇾 Libya",
        "MA" => "🇲🇦 Morocco",
        "MC" => "🇲🇨 Monaco",
        "MD" => "🇲🇩 Moldova",
        "ME" => "🇲🇪 Montenegro",
        "MF" => "🇲🇫 St. Martin",
        "MG" => "🇲🇬 Madagascar",
        "MH" => "🇲🇭 Marshall Islands",
        "MK" => "🇲🇰 Macedonia",
        "ML" => "🇲🇱 Mali",
        "MM" => "🇲🇲 Myanmar (Burma)",
        "MN" => "🇲🇳 Mongolia",
        "MO" => "🇲🇴 Macau SAR China",
        "MP" => "🇲🇵 Northern Mariana Islands",
        "MQ" => "🇲🇶 Martinique",
        "MR" => "🇲🇷 Mauritania",
        "MS" => "🇲🇸 Montserrat",
        "MT" => "🇲🇹 Malta",
        "MU" => "🇲🇺 Mauritius",
        "MV" => "🇲🇻 Maldives",
        "MW" => "🇲🇼 Malawi",
        "MX" => "🇲🇽 Mexico",
        "MY" => "🇲🇾 Malaysia",
        "MZ" => "🇲🇿 Mozambique",
        "NA" => "🇳🇦 Namibia",
        "NC" => "🇳🇨 New Caledonia",
        "NE" => "🇳🇪 Niger",
        "NF" => "🇳🇫 Norfolk Island",
        "NG" => "🇳🇬 Nigeria",
        "NI" => "🇳🇮 Nicaragua",
        "NL" => "🇳🇱 Netherlands",
        "NO" => "🇳🇴 Norway",
        "NP" => "🇳🇵 Nepal",
        "NR" => "🇳🇷 Nauru",
        "NU" => "🇳🇺 Niue",
        "NZ" => "🇳🇿 New Zealand",
        "OM" => "🇴🇲 Oman",
        "PA" => "🇵🇦 Panama",
        "PE" => "🇵🇪 Peru",
        "PF" => "🇵🇫 French Polynesia",
        "PG" => "🇵🇬 Papua New Guinea",
        "PH" => "🇵🇭 Philippines",
        "PK" => "🇵🇰 Pakistan",
        "PL" => "🇵🇱 Poland",
        "PM" => "🇵🇲 St. Pierre & Miquelon",
        "PN" => "🇵🇳 Pitcairn Islands",
        "PR" => "🇵🇷 Puerto Rico",
        "PS" => "🇵🇸 Palestinian Territories",
        "PT" => "🇵🇹 Portugal",
        "PW" => "🇵🇼 Palau",
        "PY" => "🇵🇾 Paraguay",
        "QA" => "🇶🇦 Qatar",
        "RE" => "🇷🇪 Réunion",
        "RO" => "🇷🇴 Romania",
        "RS" => "🇷🇸 Serbia",
        "RU" => "🇷🇺 Russia",
        "RW" => "🇷🇼 Rwanda",
        "SA" => "🇸🇦 Saudi Arabia",
        "SB" => "🇸🇧 Solomon Islands",
        "SC" => "🇸🇨 Seychelles",
        "SD" => "🇸🇩 Sudan",
        "SE" => "🇸🇪 Sweden",
        "SG" => "🇸🇬 Singapore",
        "SH" => "🇸🇭 St. Helena",
        "SI" => "🇸🇮 Slovenia",
        "SJ" => "🇸🇯 Svalbard & Jan Mayen",
        "SK" => "🇸🇰 Slovakia",
        "SL" => "🇸🇱 Sierra Leone",
        "SM" => "🇸🇲 San Marino",
        "SN" => "🇸🇳 Senegal",
        "SO" => "🇸🇴 Somalia",
        "SR" => "🇸🇷 Suriname",
        "SS" => "🇸🇸 South Sudan",
        "ST" => "🇸🇹 São Tomé & Príncipe",
        "SV" => "🇸🇻 El Salvador",
        "SX" => "🇸🇽 Sint Maarten",
        "SY" => "🇸🇾 Syria",
        "SZ" => "🇸🇿 Swaziland",
        "TA" => "🇹🇦 Tristan da Cunha",
        "TC" => "🇹🇨 Turks & Caicos Islands",
        "TD" => "🇹🇩 Chad",
        "TF" => "🇹🇫 French Southern Territories",
        "TG" => "🇹🇬 Togo",
        "TH" => "🇹🇭 Thailand",
        "TJ" => "🇹🇯 Tajikistan",
        "TK" => "🇹🇰 Tokelau",
        "TL" => "🇹🇱 Timor-Leste",
        "TM" => "🇹🇲 Turkmenistan",
        "TN" => "🇹🇳 Tunisia",
        "TO" => "🇹🇴 Tonga",
        "TR" => "🇹🇷 Turkey",
        "TT" => "🇹🇹 Trinidad & Tobago",
        "TV" => "🇹🇻 Tuvalu",
        "TW" => "🇹🇼 Taiwan",
        "TZ" => "🇹🇿 Tanzania",
        "UA" => "🇺🇦 Ukraine",
        "UG" => "🇺🇬 Uganda",
        "UM" => "🇺🇲 U.S. Outlying Islands",
        "UN" => "🇺🇳 United Nations",
        "US" => "🇺🇸 United States",
        "UY" => "🇺🇾 Uruguay",
        "UZ" => "🇺🇿 Uzbekistan",
        "VA" => "🇻🇦 Vatican City",
        "VC" => "🇻🇨 St. Vincent & Grenadines",
        "VE" => "🇻🇪 Venezuela",
        "VG" => "🇻🇬 British Virgin Islands",
        "VI" => "🇻🇮 U.S. Virgin Islands",
        "VN" => "🇻🇳 Vietnam",
        "VU" => "🇻🇺 Vanuatu",
        "WF" => "🇼🇫 Wallis & Futuna",
        "WS" => "🇼🇸 Samoa",
        "XK" => "🇽🇰 Kosovo",
        "YE" => "🇾🇪 Yemen",
        "YT" => "🇾🇹 Mayotte",
        "ZA" => "🇿🇦 South Africa",
        "ZM" => "🇿🇲 Zambia",
        "ZW" => "🇿🇼 Zimbabwe",
    );
    return $countryArr;
}

function getCountryItem($code)
{
    foreach (getCountries() as $key => $value) {
        if ($key == $code) {
            return $value;
        }
    }
    return null;
}

function bootstrap_pagination(\WP_Query $wp_query = null, $echo = true)
{

    if (null === $wp_query) {
        global $wp_query;
    }

    $pages = paginate_links(
        [
            'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format'       => '?paged=%#%',
            'current'      => max(1, get_query_var('paged')),
            'total'        => $wp_query->max_num_pages,
            'type'         => 'array',
            'show_all'     => false,
            'end_size'     => 3,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => __('« Trước'),
            'next_text'    => __('Tiếp theo »'),
            'add_args'     => false,
            'add_fragment' => ''
        ]
    );

    if (is_array($pages)) {
        //$paged = ( get_query_var( 'paged' ) == 0 ) ? 1 : get_query_var( 'paged' );

        $pagination = '<div class="pagination"><ul class="pagination">';

        foreach ($pages as $page) {
            $pagination .= '<li class="page-item' . (strpos($page, 'current') !== false ? ' active' : '') . '"> ' . str_replace('page-numbers', 'page-link', $page) . '</li>';
        }

        $pagination .= '</ul></div>';

        if ($echo) {
            echo $pagination;
        } else {
            return $pagination;
        }
    }

    return null;
}