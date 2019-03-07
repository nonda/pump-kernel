<?php
namespace Nonda\Util;

class CountryInfo
{
    /**
     * Country code
     *
     * @see https://en.wikipedia.org/wiki/ISO_3166-1
     *
     * @var array
     */
    protected static $countryCodes = [
        "AF", //Afghanistan
        "AX", //Åland Islands
        "AL", //Albania
        "DZ", //Algeria
        "AS", //American Samoa
        "AD", //Andorra
        "AO", //Angola
        "AI", //Anguilla
        "AQ", //Antarctica
        "AG", //Antigua and Barbuda
        "AR", //Argentina
        "AM", //Armenia
        "AW", //Aruba
        "AU", //Australia
        "AT", //Austria
        "AZ", //Azerbaijan
        "BS", //Bahamas
        "BH", //Bahrain
        "BD", //Bangladesh
        "BB", //Barbados
        "BY", //Belarus
        "BE", //Belgium
        "BZ", //Belize
        "BJ", //Benin
        "BM", //Bermuda
        "BT", //Bhutan
        "BO", //Bolivia (Plurinational State of)
        "BQ", //"Bonaire, Sint Eustatius and Saba"
        "BA", //Bosnia and Herzegovina
        "BW", //Botswana
        "BV", //Bouvet Island
        "BR", //Brazil
        "IO", //British Indian Ocean Territory
        "BN", //Brunei Darussalam
        "BG", //Bulgaria
        "BF", //Burkina Faso
        "BI", //Burundi
        "CV", //Cabo Verde
        "KH", //Cambodia
        "CM", //Cameroon
        "CA", //Canada
        "KY", //Cayman Islands
        "CF", //Central African Republic
        "TD", //Chad
        "CL", //Chile
        "CN", //China
        "CX", //Christmas Island
        "CC", //Cocos (Keeling) Islands
        "CO", //Colombia
        "KM", //Comoros
        "CG", //Congo
        "CD", //Congo (Democratic Republic of the)
        "CK", //Cook Islands
        "CR", //Costa Rica
        "CI", //Côte d'Ivoire
        "HR", //Croatia
        "CU", //Cuba
        "CW", //Curaçao
        "CY", //Cyprus
        "CZ", //Czechia
        "DK", //Denmark
        "DJ", //Djibouti
        "DM", //Dominica
        "DO", //Dominican Republic
        "EC", //Ecuador
        "EG", //Egypt
        "SV", //El Salvador
        "GQ", //Equatorial Guinea
        "ER", //Eritrea
        "EE", //Estonia
        "ET", //Ethiopia
        "FK", //Falkland Islands (Malvinas)
        "FO", //Faroe Islands
        "FJ", //Fiji
        "FI", //Finland
        "FR", //France
        "GF", //French Guiana
        "PF", //French Polynesia
        "TF", //French Southern Territories
        "GA", //Gabon
        "GM", //Gambia
        "GE", //Georgia
        "DE", //Germany
        "GH", //Ghana
        "GI", //Gibraltar
        "GR", //Greece
        "GL", //Greenland
        "GD", //Grenada
        "GP", //Guadeloupe
        "GU", //Guam
        "GT", //Guatemala
        "GG", //Guernsey
        "GN", //Guinea
        "GW", //Guinea-Bissau
        "GY", //Guyana
        "HT", //Haiti
        "HM", //Heard Island and McDonald Islands
        "VA", //Holy See
        "HN", //Honduras
        "HK", //Hong Kong
        "HU", //Hungary
        "IS", //Iceland
        "IN", //India
        "ID", //Indonesia
        "IR", //Iran (Islamic Republic of)
        "IQ", //Iraq
        "IE", //Ireland
        "IM", //Isle of Man
        "IL", //Israel
        "IT", //Italy
        "JM", //Jamaica
        "JP", //Japan
        "JE", //Jersey
        "JO", //Jordan
        "KZ", //Kazakhstan
        "KE", //Kenya
        "KI", //Kiribati
        "KP", //Korea (Democratic People's Republic of)
        "KR", //Korea (Republic of)
        "KW", //Kuwait
        "KG", //Kyrgyzstan
        "LA", //Lao People's Democratic Republic
        "LV", //Latvia
        "LB", //Lebanon
        "LS", //Lesotho
        "LR", //Liberia
        "LY", //Libya
        "LI", //Liechtenstein
        "LT", //Lithuania
        "LU", //Luxembourg
        "MO", //Macao
        "MK", //Macedonia (the former Yugoslav Republic of)
        "MG", //Madagascar
        "MW", //Malawi
        "MY", //Malaysia
        "MV", //Maldives
        "ML", //Mali
        "MT", //Malta
        "MH", //Marshall Islands
        "MQ", //Martinique
        "MR", //Mauritania
        "MU", //Mauritius
        "YT", //Mayotte
        "MX", //Mexico
        "FM", //Micronesia (Federated States of)
        "MD", //Moldova (Republic of)
        "MC", //Monaco
        "MN", //Mongolia
        "ME", //Montenegro
        "MS", //Montserrat
        "MA", //Morocco
        "MZ", //Mozambique
        "MM", //Myanmar
        "NA", //Namibia
        "NR", //Nauru
        "NP", //Nepal
        "NL", //Netherlands
        "NC", //New Caledonia
        "NZ", //New Zealand
        "NI", //Nicaragua
        "NE", //Niger
        "NG", //Nigeria
        "NU", //Niue
        "NF", //Norfolk Island
        "MP", //Northern Mariana Islands
        "NO", //Norway
        "OM", //Oman
        "PK", //Pakistan
        "PW", //Palau
        "PS", //"Palestine, State of"
        "PA", //Panama
        "PG", //Papua New Guinea
        "PY", //Paraguay
        "PE", //Peru
        "PH", //Philippines
        "PN", //Pitcairn
        "PL", //Poland
        "PT", //Portugal
        "PR", //Puerto Rico
        "QA", //Qatar
        "RE", //Réunion
        "RO", //Romania
        "RU", //Russian Federation
        "RW", //Rwanda
        "BL", //Saint Barthélemy
        "SH", //"Saint Helena, Ascension and Tristan da Cunha"
        "KN", //Saint Kitts and Nevis
        "LC", //Saint Lucia
        "MF", //Saint Martin (French part)
        "PM", //Saint Pierre and Miquelon
        "VC", //Saint Vincent and the Grenadines
        "WS", //Samoa
        "SM", //San Marino
        "ST", //Sao Tome and Principe
        "SA", //Saudi Arabia
        "SN", //Senegal
        "RS", //Serbia
        "SC", //Seychelles
        "SL", //Sierra Leone
        "SG", //Singapore
        "SX", //Sint Maarten (Dutch part)
        "SK", //Slovakia
        "SI", //Slovenia
        "SB", //Solomon Islands
        "SO", //Somalia
        "ZA", //South Africa
        "GS", //South Georgia and the South Sandwich Islands
        "SS", //South Sudan
        "ES", //Spain
        "LK", //Sri Lanka
        "SD", //Sudan
        "SR", //Suriname
        "SJ", //Svalbard and Jan Mayen
        "SZ", //Swaziland
        "SE", //Sweden
        "CH", //Switzerland
        "SY", //Syrian Arab Republic
        "TW", //"Taiwan, Province of China[a]"
        "TJ", //Tajikistan
        "TZ", //"Tanzania, United Republic of"
        "TH", //Thailand
        "TL", //Timor-Leste
        "TG", //Togo
        "TK", //Tokelau
        "TO", //Tonga
        "TT", //Trinidad and Tobago
        "TN", //Tunisia
        "TR", //Turkey
        "TM", //Turkmenistan
        "TC", //Turks and Caicos Islands
        "TV", //Tuvalu
        "UG", //Uganda
        "UA", //Ukraine
        "AE", //United Arab Emirates
        "GB", //United Kingdom of Great Britain and Northern Ireland
        "UK", //United Kingdom of Great Britain and Northern Ireland fork
        "US", //United States of America
        "UM", //United States Minor Outlying Islands
        "UY", //Uruguay
        "UZ", //Uzbekistan
        "VU", //Vanuatu
        "VE", //Venezuela (Bolivarian Republic of)
        "VN", //Viet Nam
        "VG", //Virgin Islands (British)
        "VI", //Virgin Islands (U.S.)
        "WF", //Wallis and Futuna
        "EH", //Western Sahara
        "YE", //Yemen
        "ZM", //Zambia
        "ZW", //Zimbabwe
    ];

    /**
     * Currency code list
     *
     * @see https://en.wikipedia.org/wiki/ISO_4217
     *
     * @var array
     */
    protected static $currencyList = [
        'AED', //United Arab Emirates dirham	 United Arab Emirates
        'AFN', //Afghan afghani	 Afghanistan
        'ALL', //Albanian lek	 Albania
        'AMD', //Armenian dram	 Armenia
        'ANG', //Netherlands Antillean guilder	 Curaçao (CW),  Sint Maarten (SX)
        'AOA', //Angolan kwanza	 Angola
        'ARS', //Argentine peso	 Argentina
        'AUD', //Australian dollar	 Australia,  Christmas Island (CX),  Cocos (Keeling) Islands (CC),  Heard Island and McDonald Islands (HM),  Kiribati (KI),  Nauru (NR),  Norfolk Island (NF),  Tuvalu (TV)
        'AWG', //Aruban florin	 Aruba
        'AZN', //Azerbaijani manat	 Azerbaijan
        'BAM', //Bosnia and Herzegovina convertible mark	 Bosnia and Herzegovina
        'BBD', //Barbados dollar	 Barbados
        'BDT', //Bangladeshi taka	 Bangladesh
        'BGN', //Bulgarian lev	 Bulgaria
        'BHD', //Bahraini dinar	 Bahrain
        'BIF', //Burundian franc	 Burundi
        'BMD', //Bermudian dollar	 Bermuda
        'BND', //Brunei dollar	 Brunei
        'BOB', //Boliviano	 Bolivia
        'BOV', //Bolivian Mvdol (funds code)	 Bolivia
        'BRL', //Brazilian real	 Brazil
        'BSD', //Bahamian dollar	 Bahamas
        'BTN', //Bhutanese ngultrum	 Bhutan
        'BWP', //Botswana pula	 Botswana
        'BYN', //Belarusian ruble	 Belarus
        'BZD', //Belize dollar	 Belize
        'CAD', //Canadian dollar	 Canada
        'CDF', //Congolese franc	 Democratic Republic of the Congo
        'CHE', //WIR Euro (complementary currency)	  Switzerland
        'CHF', //Swiss franc	  Switzerland,  Liechtenstein (LI)
        'CHW', //WIR Franc (complementary currency)	  Switzerland
        'CLF', //Unidad de Fomento (funds code)	 Chile
        'CLP', //Chilean peso	 Chile
        'CNY', //Chinese yuan	 China
        'COP', //Colombian peso	 Colombia
        'COU', //	Unidad de Valor Real (UVR) (funds code)[7]	 Colombia
        'CRC', //Costa Rican colon	 Costa Rica
        'CUC', //Cuban convertible peso	 Cuba
        'CUP', //Cuban peso	 Cuba
        'CVE', //Cape Verde escudo	 Cape Verde
        'CZK', //Czech koruna	 Czechia [8]
        'DJF', //Djiboutian franc	 Djibouti
        'DKK', //Danish krone	 Denmark,  Faroe Islands (FO),  Greenland (GL)
        'DOP', //Dominican peso	 Dominican Republic
        'DZD', //Algerian dinar	 Algeria
        'EGP', //Egyptian pound	 Egypt
        'ERN', //Eritrean nakfa	 Eritrea
        'ETB', //Ethiopian birr	 Ethiopia
        'EUR', //Euro	 Andorra (AD),  Austria (AT),  Belgium (BE),  Cyprus (CY),  Estonia (EE),  Finland (FI),  France (FR),  Germany (DE),  Greece (GR),  Guadeloupe (GP),  Ireland (IE),  Italy (IT),  Latvia (LV),  Lithuania (LT),  Luxembourg (LU),  Malta (MT),  Martinique (MQ),  Mayotte (YT),  Monaco (MC),  Montenegro (ME),  Netherlands (NL),  Portugal (PT),  Réunion (RE),  Saint Barthélemy (BL),  Saint Pierre and Miquelon (PM),  San Marino (SM),  Slovakia (SK),  Slovenia (SI),  Spain (ES)
        'FJD', //Fiji dollar	 Fiji
        'FKP', //Falkland Islands pound	 Falkland Islands (pegged to GBP 1:1)
        'GBP', //Pound sterling	 United Kingdom, the  Isle of Man (IM, see Manx pound),  Jersey (JE, see Jersey pound), and  Guernsey (GG, see Guernsey pound)
        'GEL', //Georgian lari	 Georgia
        'GHS', //Ghanaian cedi	 Ghana
        'GIP', //Gibraltar pound	 Gibraltar (pegged to GBP 1:1)
        'GMD', //Gambian dalasi	 Gambia
        'GNF', //Guinean franc	 Guinea
        'GTQ', //Guatemalan quetzal	 Guatemala
        'GYD', //Guyanese dollar	 Guyana
        'HKD', //Hong Kong dollar	 Hong Kong
        'HNL', //Honduran lempira	 Honduras
        'HRK', //Croatian kuna	 Croatia
        'HTG', //Haitian gourde	 Haiti
        'HUF', //Hungarian forint	 Hungary
        'IDR', //Indonesian rupiah	 Indonesia
        'ILS', //Israeli new shekel	 Israel
        'INR', //Indian rupee	 India,  Bhutan
        'IQD', //Iraqi dinar	 Iraq
        'IRR', //Iranian rial	 Iran
        'ISK', //Icelandic króna	 Iceland
        'JMD', //Jamaican dollar	 Jamaica
        'JOD', //Jordanian dinar	 Jordan
        'JPY', //Japanese yen	 Japan
        'KES', //Kenyan shilling	 Kenya
        'KGS', //Kyrgyzstani som	 Kyrgyzstan
        'KHR', //Cambodian riel	 Cambodia
        'KMF', //Comoro franc	 Comoros
        'KPW', //North Korean won	 North Korea
        'KRW', //South Korean won	 South Korea
        'KWD', //Kuwaiti dinar	 Kuwait
        'KYD', //Cayman Islands dollar	 Cayman Islands
        'KZT', //Kazakhstani tenge	 Kazakhstan
        'LAK', //Lao kip	 Laos
        'LBP', //Lebanese pound	 Lebanon
        'LKR', //Sri Lankan rupee	 Sri Lanka
        'LRD', //Liberian dollar	 Liberia
        'LSL', //Lesotho loti	 Lesotho
        'LYD', //Libyan dinar	 Libya
        'MAD', //Moroccan dirham	 Morocco
        'MDL', //Moldovan leu	 Moldova
        'MGA', //]	Malagasy ariary	 Madagascar
        'MKD', //Macedonian denar	 Macedonia
        'MMK', //Myanmar kyat	 Myanmar
        'MNT', //Mongolian tögrög	 Mongolia
        'MOP', //Macanese pataca	 Macao
        'MRO', //]	Mauritanian ouguiya	 Mauritania
        'MUR', //Mauritian rupee	 Mauritius
        'MVR', //Maldivian rufiyaa	 Maldives
        'MWK', //Malawian kwacha	 Malawi
        'MXN', //Mexican peso	 Mexico
        'MXV', //Mexican Unidad de Inversion (UDI) (funds code)	 Mexico
        'MYR', //Malaysian ringgit	 Malaysia
        'MZN', //Mozambican metical	 Mozambique
        'NAD', //Namibian dollar	 Namibia
        'NGN', //Nigerian naira	 Nigeria
        'NIO', //Nicaraguan córdoba	 Nicaragua
        'NOK', //Norwegian krone	 Norway,  Svalbard and  Jan Mayen (SJ),  Bouvet Island (BV)
        'NPR', //Nepalese rupee	   Nepal
        'NZD', //New Zealand dollar	 New Zealand,  Cook Islands (CK),  Niue (NU),  Pitcairn Islands (PN; see also Pitcairn Islands dollar),  Tokelau (TK)
        'OMR', //Omani rial	 Oman
        'PAB', //Panamanian balboa	 Panama
        'PEN', //Peruvian Sol	 Peru
        'PGK', //Papua New Guinean kina	 Papua New Guinea
        'PHP', //Philippine piso[10]	 Philippines
        'PKR', //Pakistani rupee	 Pakistan
        'PLN', //Polish złoty	 Poland
        'PYG', //Paraguayan guaraní	 Paraguay
        'QAR', //Qatari riyal	 Qatar
        'RON', //Romanian leu	 Romania
        'RSD', //Serbian dinar	 Serbia
        'RUB', //Russian ruble	 Russia
        'RWF', //Rwandan franc	 Rwanda
        'SAR', //Saudi riyal	 Saudi Arabia
        'SBD', //Solomon Islands dollar	 Solomon Islands
        'SCR', //Seychelles rupee	 Seychelles
        'SDG', //Sudanese pound	 Sudan
        'SEK', //Swedish krona/kronor	 Sweden
        'SGD', //Singapore dollar	 Singapore
        'SHP', //Saint Helena pound	 Saint Helena (SH-SH),  Ascension Island (SH-AC),  Tristan da Cunha
        'SLL', //Sierra Leonean leone	 Sierra Leone
        'SOS', //Somali shilling	 Somalia
        'SRD', //Surinamese dollar	 Suriname
        'SSP', //South Sudanese pound	 South Sudan
        'STD', //São Tomé and Príncipe dobra	 São Tomé and Príncipe
        'SVC', //Salvadoran colón	 El Salvador
        'SYP', //Syrian pound	 Syria
        'SZL', //Swazi lilangeni	 Swaziland
        'THB', //Thai baht	 Thailand
        'TJS', //Tajikistani somoni	 Tajikistan
        'TMT', //Turkmenistan manat	 Turkmenistan
        'TND', //Tunisian dinar	 Tunisia
        'TOP', //Tongan paʻanga	 Tonga
        'TRY', //Turkish lira	 Turkey
        'TTD', //Trinidad and Tobago dollar	 Trinidad and Tobago
        'TWD', //New Taiwan dollar	 Taiwan
        'TZS', //Tanzanian shilling	 Tanzania
        'UAH', //Ukrainian hryvnia	 Ukraine
        'UGX', //Ugandan shilling	 Uganda
        'USD', //United States dollar	 United States,  American Samoa (AS),  Barbados (BB) (as well as Barbados Dollar),  Bermuda (BM) (as well as Bermudian Dollar),  British Indian Ocean Territory (IO) (also uses GBP),  British Virgin Islands (VG),  Caribbean Netherlands (BQ - Bonaire, Sint Eustatius and Saba),  Ecuador (EC),  El Salvador (SV),  Guam (GU),  Haiti (HT),  Marshall Islands (MH),  Federated States of Micronesia (FM),  Northern Mariana Islands (MP),  Palau (PW),  Panama (PA) (as well as Panamanian Balboa),  Puerto Rico (PR),  Timor-Leste (TL),  Turks and Caicos Islands (TC),  U.S. Virgin Islands (VI),  United States Minor Outlying Islands
        'USN', //United States dollar (next day) (funds code)	 United States
        'UYI', //Uruguay Peso en Unidades Indexadas (URUIURUI) (funds code)	 Uruguay
        'UYU', //Uruguayan peso	 Uruguay
        'UZS', //Uzbekistan som	 Uzbekistan
        'VEF', //Venezuelan bolívar	 Venezuela
        'VND', //Vietnamese đồng	 Vietnam
        'VUV', //Vanuatu vatu	 Vanuatu
        'WST', //Samoan tala	 Samoa
        'XAF', //CFA franc BEAC	 Cameroon (CM),  Central African Republic (CF),  Republic of the Congo (CG),  Chad (TD),  Equatorial Guinea (GQ),  Gabon (GA)
        'XAG', //Silver (one troy ounce)	
        'XAU', //Gold (one troy ounce)	
        'XBA', //European Composite Unit (EURCO) (bond market unit)	
        'XBB', //European Monetary Unit (E.M.U.-6) (bond market unit)	
        'XBC', //European Unit of Account 9 (E.U.A.-9) (bond market unit)	
        'XBD', //European Unit of Account 17 (E.U.A.-17) (bond market unit)	
        'XCD', //East Caribbean dollar	 Anguilla (AI),  Antigua and Barbuda (AG),  Dominica (DM),  Grenada (GD),  Montserrat (MS),  Saint Kitts and Nevis (KN),  Saint Lucia (LC),  Saint Vincent and the Grenadines (VC)
        'XDR', //Special drawing rights	International Monetary Fund
        'XOF', //CFA franc BCEAO	 Benin (BJ),  Burkina Faso (BF),  Côte d'Ivoire (CI),  Guinea-Bissau (GW),  Mali (ML),  Niger (NE),  Senegal (SN),  Togo (TG)
        'XPD', //Palladium (one troy ounce)	
        'XPF', //CFP franc (franc Pacifique)	French territories of the Pacific Ocean:  French Polynesia (PF),  New Caledonia (NC),  Wallis and Futuna (WF)
        'XPT', //Platinum (one troy ounce)	
        'XSU', //SUCRE	Unified System for Regional Compensation (SUCRE)[11]
        'XTS', //Code reserved for testing purposes	
        'XUA', //ADB Unit of Account	African Development Bank[12]
        'XXX', //No currency	
        'YER', //Yemeni rial	 Yemen
        'ZAR', //South African rand	 South Africa
        'ZMW', //Zambian kwacha	 Zambia
        'ZWL', //Zimbabwean dollar A/10	 Zimbabwe
    ];

    public static function getCountryCodes()
    {
        return self::$countryCodes;
    }

    public static function isValidCountryCode($code)
    {
        if ('CUSTOM' === $code) {
            return true;
        }

        if (in_array($code, self::$countryCodes)) {
            return true;
        }

        return false;
    }

    public static function getCurrencyList()
    {
        return self::$currencyList;
    }

    public static function isValidCurrency($code)
    {
        if ('CUSTOM' === $code) {
            return true;
        }

        if (in_array($code, self::$currencyList)) {
            return true;
        }

        return false;
    }
}
