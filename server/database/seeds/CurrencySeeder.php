<?php

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Currency::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $currencies = [
            [1,"AFN","Afghan Afghani"],
            [2,"AFA","Afghan Afghani (1927–2002)"],
            [3,"ALL","Albanian Lek"],
            [4,"ALK","Albanian Lek (1946–1965)"],
            [5,"DZD","Algerian Dinar"],
            [6,"ADP","Andorran Peseta"],
            [7,"AOA","Angolan Kwanza"],
            [8,"AOK","Angolan Kwanza (1977–1991)"],
            [9,"AON","Angolan New Kwanza (1990–2000)"],
            [10,"AOR","Angolan Readjusted Kwanza (1995–1999)"],
            [11,"ARA","Argentine Austral"],
            [12,"ARS","Argentine Peso"],
            [13,"ARM","Argentine Peso (1881–1970)"],
            [14,"ARP","Argentine Peso (1983–1985)"],
            [15,"ARL","Argentine Peso Ley (1970–1983)"],
            [16,"AMD","Armenian Dram"],
            [17,"AWG","Aruban Florin"],
            [18,"AUD","Australian Dollar"],
            [19,"ATS","Austrian Schilling"],
            [20,"AZN","Azerbaijani Manat"],
            [21,"AZM","Azerbaijani Manat (1993–2006)"],
            [22,"BSD","Bahamian Dollar"],
            [23,"BHD","Bahraini Dinar"],
            [24,"BDT","Bangladeshi Taka"],
            [25,"BBD","Barbadian Dollar"],
            [26,"BYN","Belarusian Ruble"],
            [27,"BYB","Belarusian Ruble (1994–1999)"],
            [28,"BYR","Belarusian Ruble (2000–2016)"],
            [29,"BEF","Belgian Franc"],
            [30,"BEC","Belgian Franc (convertible)"],
            [31,"BEL","Belgian Franc (financial)"],
            [32,"BZD","Belize Dollar"],
            [33,"BMD","Bermudan Dollar"],
            [34,"BTN","Bhutanese Ngultrum"],
            [35,"BOB","Bolivian Boliviano"],
            [36,"BOL","Bolivian Boliviano (1863–1963)"],
            [37,"BOV","Bolivian Mvdol"],
            [38,"BOP","Bolivian Peso"],
            [39,"BAM","Bosnia-Herzegovina Convertible Mark"],
            [40,"BAD","Bosnia-Herzegovina Dinar (1992–1994)"],
            [41,"BAN","Bosnia-Herzegovina New Dinar (1994–1997)"],
            [42,"BWP","Botswanan Pula"],
            [43,"BRC","Brazilian Cruzado (1986–1989)"],
            [44,"BRZ","Brazilian Cruzeiro (1942–1967)"],
            [45,"BRE","Brazilian Cruzeiro (1990–1993)"],
            [46,"BRR","Brazilian Cruzeiro (1993–1994)"],
            [47,"BRN","Brazilian New Cruzado (1989–1990)"],
            [48,"BRB","Brazilian New Cruzeiro (1967–1986)"],
            [49,"BRL","Brazilian Real"],
            [50,"GBP","British Pound"],
            [51,"BND","Brunei Dollar"],
            [52,"BGL","Bulgarian Hard Lev"],
            [53,"BGN","Bulgarian Lev"],
            [54,"BGO","Bulgarian Lev (1879–1952)"],
            [55,"BGM","Bulgarian Socialist Lev"],
            [56,"BUK","Burmese Kyat"],
            [57,"BIF","Burundian Franc"],
            [58,"XPF","CFP Franc"],
            [59,"KHR","Cambodian Riel"],
            [60,"CAD","Canadian Dollar"],
            [61,"CVE","Cape Verdean Escudo"],
            [62,"KYD","Cayman Islands Dollar"],
            [63,"XAF","Central African CFA Franc"],
            [64,"CLE","Chilean Escudo"],
            [65,"CLP","Chilean Peso"],
            [66,"CLF","Chilean Unit of Account (UF)"],
            [67,"CNX","Chinese People’s Bank Dollar"],
            [68,"CNY","Chinese Yuan"],
            [69,"COP","Colombian Peso"],
            [70,"COU","Colombian Real Value Unit"],
            [71,"KMF","Comorian Franc"],
            [72,"CDF","Congolese Franc"],
            [73,"CRC","Costa Rican Colón"],
            [74,"HRD","Croatian Dinar"],
            [75,"HRK","Croatian Kuna"],
            [76,"CUC","Cuban Convertible Peso"],
            [77,"CUP","Cuban Peso"],
            [78,"CYP","Cypriot Pound"],
            [79,"CZK","Czech Koruna"],
            [80,"CSK","Czechoslovak Hard Koruna"],
            [81,"DKK","Danish Krone"],
            [82,"DJF","Djiboutian Franc"],
            [83,"DOP","Dominican Peso"],
            [84,"NLG","Dutch Guilder"],
            [85,"XCD","East Caribbean Dollar"],
            [86,"DDM","East German Mark"],
            [87,"ECS","Ecuadorian Sucre"],
            [88,"ECV","Ecuadorian Unit of Constant Value"],
            [89,"EGP","Egyptian Pound"],
            [90,"GQE","Equatorial Guinean Ekwele"],
            [91,"ERN","Eritrean Nakfa"],
            [92,"EEK","Estonian Kroon"],
            [93,"ETB","Ethiopian Birr"],
            [94,"EUR","Euro"],
            [95,"XEU","European Currency Unit"],
            [96,"FKP","Falkland Islands Pound"],
            [97,"FJD","Fijian Dollar"],
            [98,"FIM","Finnish Markka"],
            [99,"FRF","French Franc"],
            [100,"XFO","French Gold Franc"],
            [101,"XFU","French UIC-Franc"],
            [102,"GMD","Gambian Dalasi"],
            [103,"GEK","Georgian Kupon Larit"],
            [104,"GEL","Georgian Lari"],
            [105,"DEM","German Mark"],
            [106,"GHS","Ghanaian Cedi"],
            [107,"GHC","Ghanaian Cedi (1979–2007)"],
            [108,"GIP","Gibraltar Pound"],
            [109,"GRD","Greek Drachma"],
            [110,"GTQ","Guatemalan Quetzal"],
            [111,"GWP","Guinea-Bissau Peso"],
            [112,"GNF","Guinean Franc"],
            [113,"GNS","Guinean Syli"],
            [114,"GYD","Guyanaese Dollar"],
            [115,"HTG","Haitian Gourde"],
            [116,"HNL","Honduran Lempira"],
            [117,"HKD","Hong Kong Dollar"],
            [118,"HUF","Hungarian Forint"],
            [119,"ISK","Icelandic Króna"],
            [120,"ISJ","Icelandic Króna (1918–1981)"],
            [121,"INR","Indian Rupee"],
            [122,"IDR","Indonesian Rupiah"],
            [123,"IRR","Iranian Rial"],
            [124,"IQD","Iraqi Dinar"],
            [125,"IEP","Irish Pound"],
            [126,"ILS","Israeli New Shekel"],
            [127,"ILP","Israeli Pound"],
            [128,"ILR","Israeli Shekel (1980–1985)"],
            [129,"ITL","Italian Lira"],
            [130,"JMD","Jamaican Dollar"],
            [131,"JPY","Japanese Yen"],
            [132,"JOD","Jordanian Dinar"],
            [133,"KZT","Kazakhstani Tenge"],
            [134,"KES","Kenyan Shilling"],
            [135,"KWD","Kuwaiti Dinar"],
            [136,"KGS","Kyrgystani Som"],
            [137,"LAK","Laotian Kip"],
            [138,"LVL","Latvian Lats"],
            [139,"LVR","Latvian Ruble"],
            [140,"LBP","Lebanese Pound"],
            [141,"LSL","Lesotho Loti"],
            [142,"LRD","Liberian Dollar"],
            [143,"LYD","Libyan Dinar"],
            [144,"LTL","Lithuanian Litas"],
            [145,"LTT","Lithuanian Talonas"],
            [146,"LUL","Luxembourg Financial Franc"],
            [147,"LUC","Luxembourgian Convertible Franc"],
            [148,"LUF","Luxembourgian Franc"],
            [149,"MOP","Macanese Pataca"],
            [150,"MKD","Macedonian Denar"],
            [151,"MKN","Macedonian Denar (1992–1993)"],
            [152,"MGA","Malagasy Ariary"],
            [153,"MGF","Malagasy Franc"],
            [154,"MWK","Malawian Kwacha"],
            [155,"MYR","Malaysian Ringgit"],
            [156,"MVR","Maldivian Rufiyaa"],
            [157,"MVP","Maldivian Rupee (1947–1981)"],
            [158,"MLF","Malian Franc"],
            [159,"MTL","Maltese Lira"],
            [160,"MTP","Maltese Pound"],
            [161,"MRO","Mauritanian Ouguiya"],
            [162,"MUR","Mauritian Rupee"],
            [163,"MXV","Mexican Investment Unit"],
            [164,"MXN","Mexican Peso"],
            [165,"MXP","Mexican Silver Peso (1861–1992)"],
            [166,"MDC","Moldovan Cupon"],
            [167,"MDL","Moldovan Leu"],
            [168,"MCF","Monegasque Franc"],
            [169,"MNT","Mongolian Tugrik"],
            [170,"MAD","Moroccan Dirham"],
            [171,"MAF","Moroccan Franc"],
            [172,"MZE","Mozambican Escudo"],
            [173,"MZN","Mozambican Metical"],
            [174,"MZM","Mozambican Metical (1980–2006)"],
            [175,"MMK","Myanmar Kyat"],
            [176,"NAD","Namibian Dollar"],
            [177,"NPR","Nepalese Rupee"],
            [178,"ANG","Netherlands Antillean Guilder"],
            [179,"TWD","New Taiwan Dollar"],
            [180,"NZD","New Zealand Dollar"],
            [181,"NIO","Nicaraguan Córdoba"],
            [182,"NIC","Nicaraguan Córdoba (1988–1991)"],
            [183,"NGN","Nigerian Naira"],
            [184,"KPW","North Korean Won"],
            [185,"NOK","Norwegian Krone"],
            [186,"OMR","Omani Rial"],
            [187,"PKR","Pakistani Rupee"],
            [188,"PAB","Panamanian Balboa"],
            [189,"PGK","Papua New Guinean Kina"],
            [190,"PYG","Paraguayan Guarani"],
            [191,"PEI","Peruvian Inti"],
            [192,"PEN","Peruvian Sol"],
            [193,"PES","Peruvian Sol (1863–1965)"],
            [194,"PHP","Philippine Peso"],
            [195,"PLN","Polish Zloty"],
            [196,"PLZ","Polish Zloty (1950–1995)"],
            [197,"PTE","Portuguese Escudo"],
            [198,"GWE","Portuguese Guinea Escudo"],
            [199,"QAR","Qatari Rial"],
            [200,"XRE","RINET Funds"],
            [201,"RHD","Rhodesian Dollar"],
            [202,"RON","Romanian Leu"],
            [203,"ROL","Romanian Leu (1952–2006)"],
            [204,"RUB","Russian Ruble"],
            [205,"RUR","Russian Ruble (1991–1998)"],
            [206,"RWF","Rwandan Franc"],
            [207,"SVC","Salvadoran Colón"],
            [208,"WST","Samoan Tala"],
            [209,"SAR","Saudi Riyal"],
            [210,"RSD","Serbian Dinar"],
            [211,"CSD","Serbian Dinar (2002–2006)"],
            [212,"SCR","Seychellois Rupee"],
            [213,"SLL","Sierra Leonean Leone"],
            [214,"SGD","Singapore Dollar"],
            [215,"SKK","Slovak Koruna"],
            [216,"SIT","Slovenian Tolar"],
            [217,"SBD","Solomon Islands Dollar"],
            [218,"SOS","Somali Shilling"],
            [219,"ZAR","South African Rand"],
            [220,"ZAL","South African Rand (financial)"],
            [221,"KRH","South Korean Hwan (1953–1962)"],
            [222,"KRW","South Korean Won"],
            [223,"KRO","South Korean Won (1945–1953)"],
            [224,"SSP","South Sudanese Pound"],
            [225,"SUR","Soviet Rouble"],
            [226,"ESP","Spanish Peseta"],
            [227,"ESA","Spanish Peseta (A account)"],
            [228,"ESB","Spanish Peseta (convertible account)"],
            [229,"LKR","Sri Lankan Rupee"],
            [230,"SHP","St. Helena Pound"],
            [231,"SDD","Sudanese Dinar (1992–2007)"],
            [232,"SDG","Sudanese Pound"],
            [233,"SDP","Sudanese Pound (1957–1998)"],
            [234,"SRD","Surinamese Dollar"],
            [235,"SRG","Surinamese Guilder"],
            [236,"SZL","Swazi Lilangeni"],
            [237,"SEK","Swedish Krona"],
            [238,"CHF","Swiss Franc"],
            [239,"SYP","Syrian Pound"],
            [240,"STD","São Tomé & Príncipe Dobra"],
            [241,"TJR","Tajikistani Ruble"],
            [242,"TJS","Tajikistani Somoni"],
            [243,"TZS","Tanzanian Shilling"],
            [244,"THB","Thai Baht"],
            [245,"TPE","Timorese Escudo"],
            [246,"TOP","Tongan Paʻanga"],
            [247,"TTD","Trinidad & Tobago Dollar"],
            [248,"TND","Tunisian Dinar"],
            [249,"TRY","Turkish Lira"],
            [250,"TRL","Turkish Lira (1922–2005)"],
            [251,"TMT","Turkmenistani Manat"],
            [252,"TMM","Turkmenistani Manat (1993–2009)"],
            [253,"USD","US Dollar"],
            [254,"USN","US Dollar (Next day)"],
            [255,"USS","US Dollar (Same day)"],
            [256,"UGX","Ugandan Shilling"],
            [257,"UGS","Ugandan Shilling (1966–1987)"],
            [258,"UAH","Ukrainian Hryvnia"],
            [259,"UAK","Ukrainian Karbovanets"],
            [260,"AED","United Arab Emirates Dirham"],
            [261,"UYU","Uruguayan Peso"],
            [262,"UYP","Uruguayan Peso (1975–1993)"],
            [263,"UYI","Uruguayan Peso (Indexed Units)"],
            [264,"UZS","Uzbekistani Som"],
            [265,"VUV","Vanuatu Vatu"],
            [266,"VEF","Venezuelan Bolívar"],
            [267,"VEB","Venezuelan Bolívar (1871–2008)"],
            [268,"VND","Vietnamese Dong"],
            [269,"VNN","Vietnamese Dong (1978–1985)"],
            [270,"CHE","WIR Euro"],
            [271,"CHW","WIR Franc"],
            [272,"XOF","West African CFA Franc"],
            [273,"YDD","Yemeni Dinar"],
            [274,"YER","Yemeni Rial"],
            [275,"YUN","Yugoslavian Convertible Dinar (1990–1992)"],
            [276,"YUD","Yugoslavian Hard Dinar (1966–1990)"],
            [277,"YUM","Yugoslavian New Dinar (1994–2002)"],
            [278,"YUR","Yugoslavian Reformed Dinar (1992–1993)"],
            [279,"ZRN","Zairean New Zaire (1993–1998)"],
            [280,"ZRZ","Zairean Zaire (1971–1993)"],
            [281,"ZMW","Zambian Kwacha"],
            [282,"ZMK","Zambian Kwacha (1968–2012)"],
            [283,"ZWD","Zimbabwean Dollar (1980–2008)"],
            [284,"ZWR","Zimbabwean Dollar (2008)"],
            [285,"ZWL","Zimbabwean Dollar (2009)"]
        ];

        foreach ($currencies as $currency) {
            Currency::create([
                'id' => $currency[0],
                'code' => $currency[1],
                'name' => $currency[2],
            ]);
        }

    }
}
