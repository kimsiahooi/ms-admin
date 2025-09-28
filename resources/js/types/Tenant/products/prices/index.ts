import type { BadgeVariants } from '@/components/shared/badge';
import type { Product } from '@/types/Tenant/products';

export enum Status {
    ACTIVE = 'ACTIVE',
    INACTIVE = 'INACTIVE',
}

export const StatusLabel: Record<Status, string> = {
    [Status.ACTIVE]: 'Active',
    [Status.INACTIVE]: 'Inactive',
};

export enum Currency {
    AED = 'AED', // United Arab Emirates Dirham
    AFN = 'AFN', // Afghan Afghani
    ALL = 'ALL', // Albanian Lek
    AMD = 'AMD', // Armenian Dram
    ANG = 'ANG', // Netherlands Antillean Guilder
    AOA = 'AOA', // Angolan Kwanza
    ARS = 'ARS', // Argentine Peso
    AUD = 'AUD', // Australian Dollar
    AWG = 'AWG', // Aruban Florin
    AZN = 'AZN', // Azerbaijani Manat
    BAM = 'BAM', // Bosnia-Herzegovina Convertible Mark
    BBD = 'BBD', // Barbadian Dollar
    BDT = 'BDT', // Bangladeshi Taka
    BGN = 'BGN', // Bulgarian Lev
    BHD = 'BHD', // Bahraini Dinar
    BIF = 'BIF', // Burundian Franc
    BMD = 'BMD', // Bermudian Dollar
    BND = 'BND', // Brunei Dollar
    BOB = 'BOB', // Bolivian Boliviano
    BRL = 'BRL', // Brazilian Real
    BSD = 'BSD', // Bahamian Dollar
    BTN = 'BTN', // Bhutanese Ngultrum
    BWP = 'BWP', // Botswana Pula
    BYN = 'BYN', // Belarusian Ruble
    BZD = 'BZD', // Belize Dollar
    CAD = 'CAD', // Canadian Dollar
    CDF = 'CDF', // Congolese Franc
    CHF = 'CHF', // Swiss Franc
    CLP = 'CLP', // Chilean Peso
    CNY = 'CNY', // Chinese Yuan
    COP = 'COP', // Colombian Peso
    CRC = 'CRC', // Costa Rican Colón
    CUP = 'CUP', // Cuban Peso
    CVE = 'CVE', // Cape Verdean Escudo
    CZK = 'CZK', // Czech Koruna
    DJF = 'DJF', // Djiboutian Franc
    DKK = 'DKK', // Danish Krone
    DOP = 'DOP', // Dominican Peso
    DZD = 'DZD', // Algerian Dinar
    EGP = 'EGP', // Egyptian Pound
    ERN = 'ERN', // Eritrean Nakfa
    ETB = 'ETB', // Ethiopian Birr
    EUR = 'EUR', // Euro
    FJD = 'FJD', // Fijian Dollar
    FKP = 'FKP', // Falkland Islands Pound
    FOK = 'FOK', // Faroese Króna
    GBP = 'GBP', // British Pound
    GEL = 'GEL', // Georgian Lari
    GGP = 'GGP', // Guernsey Pound
    GHS = 'GHS', // Ghanaian Cedi
    GIP = 'GIP', // Gibraltar Pound
    GMD = 'GMD', // Gambian Dalasi
    GNF = 'GNF', // Guinean Franc
    GTQ = 'GTQ', // Guatemalan Quetzal
    GYD = 'GYD', // Guyanese Dollar
    HKD = 'HKD', // Hong Kong Dollar
    HNL = 'HNL', // Honduran Lempira
    HRK = 'HRK', // Croatian Kuna
    HTG = 'HTG', // Haitian Gourde
    HUF = 'HUF', // Hungarian Forint
    IDR = 'IDR', // Indonesian Rupiah
    ILS = 'ILS', // Israeli New Shekel
    IMP = 'IMP', // Isle of Man Pound
    INR = 'INR', // Indian Rupee
    IQD = 'IQD', // Iraqi Dinar
    IRR = 'IRR', // Iranian Rial
    ISK = 'ISK', // Icelandic Króna
    JEP = 'JEP', // Jersey Pound
    JMD = 'JMD', // Jamaican Dollar
    JOD = 'JOD', // Jordanian Dinar
    JPY = 'JPY', // Japanese Yen
    KES = 'KES', // Kenyan Shilling
    KGS = 'KGS', // Kyrgyzstani Som
    KHR = 'KHR', // Cambodian Riel
    KID = 'KID', // Kiribati Dollar
    KMF = 'KMF', // Comorian Franc
    KRW = 'KRW', // South Korean Won
    KWD = 'KWD', // Kuwaiti Dinar
    KYD = 'KYD', // Cayman Islands Dollar
    KZT = 'KZT', // Kazakhstani Tenge
    LAK = 'LAK', // Lao Kip
    LBP = 'LBP', // Lebanese Pound
    LKR = 'LKR', // Sri Lankan Rupee
    LRD = 'LRD', // Liberian Dollar
    LSL = 'LSL', // Lesotho Loti
    LYD = 'LYD', // Libyan Dinar
    MAD = 'MAD', // Moroccan Dirham
    MDL = 'MDL', // Moldovan Leu
    MGA = 'MGA', // Malagasy Ariary
    MKD = 'MKD', // Macedonian Denar
    MMK = 'MMK', // Myanmar Kyat
    MNT = 'MNT', // Mongolian Tögrög
    MOP = 'MOP', // Macanese Pataca
    MRU = 'MRU', // Mauritanian Ouguiya
    MUR = 'MUR', // Mauritian Rupee
    MVR = 'MVR', // Maldivian Rufiyaa
    MWK = 'MWK', // Malawian Kwacha
    MXN = 'MXN', // Mexican Peso
    MYR = 'MYR', // Malaysian Ringgit
    MZN = 'MZN', // Mozambican Metical
    NAD = 'NAD', // Namibian Dollar
    NGN = 'NGN', // Nigerian Naira
    NIO = 'NIO', // Nicaraguan Córdoba
    NOK = 'NOK', // Norwegian Krone
    NPR = 'NPR', // Nepalese Rupee
    NZD = 'NZD', // New Zealand Dollar
    OMR = 'OMR', // Omani Rial
    PAB = 'PAB', // Panamanian Balboa
    PEN = 'PEN', // Peruvian Sol
    PGK = 'PGK', // Papua New Guinean Kina
    PHP = 'PHP', // Philippine Peso
    PKR = 'PKR', // Pakistani Rupee
    PLN = 'PLN', // Polish Złoty
    PYG = 'PYG', // Paraguayan Guaraní
    QAR = 'QAR', // Qatari Riyal
    RON = 'RON', // Romanian Leu
    RSD = 'RSD', // Serbian Dinar
    RUB = 'RUB', // Russian Ruble
    RWF = 'RWF', // Rwandan Franc
    SAR = 'SAR', // Saudi Riyal
    SBD = 'SBD', // Solomon Islands Dollar
    SCR = 'SCR', // Seychellois Rupee
    SDG = 'SDG', // Sudanese Pound
    SEK = 'SEK', // Swedish Krona
    SGD = 'SGD', // Singapore Dollar
    SHP = 'SHP', // Saint Helena Pound
    SLE = 'SLE', // Sierra Leonean Leone
    SLL = 'SLL', // Old Leone
    SOS = 'SOS', // Somali Shilling
    SRD = 'SRD', // Surinamese Dollar
    SSP = 'SSP', // South Sudanese Pound
    STN = 'STN', // São Tomé and Príncipe Dobra
    SYP = 'SYP', // Syrian Pound
    SZL = 'SZL', // Eswatini Lilangeni
    THB = 'THB', // Thai Baht
    TJS = 'TJS', // Tajikistani Somoni
    TMT = 'TMT', // Turkmenistani Manat
    TND = 'TND', // Tunisian Dinar
    TOP = 'TOP', // Tongan Paʻanga
    TRY = 'TRY', // Turkish Lira
    TTD = 'TTD', // Trinidad and Tobago Dollar
    TVD = 'TVD', // Tuvaluan Dollar
    TWD = 'TWD', // New Taiwan Dollar
    TZS = 'TZS', // Tanzanian Shilling
    UAH = 'UAH', // Ukrainian Hryvnia
    UGX = 'UGX', // Ugandan Shilling
    USD = 'USD', // United States Dollar
    UYU = 'UYU', // Uruguayan Peso
    UZS = 'UZS', // Uzbekistani So'm
    VES = 'VES', // Venezuelan Bolívar Soberano
    VND = 'VND', // Vietnamese Đồng
    VUV = 'VUV', // Vanuatu Vatu
    WST = 'WST', // Samoan Tālā
    XAF = 'XAF', // Central African CFA Franc
    XCD = 'XCD', // East Caribbean Dollar
    XOF = 'XOF', // West African CFA Franc
    XPF = 'XPF', // CFP Franc
    YER = 'YER', // Yemeni Rial
    ZAR = 'ZAR', // South African Rand
    ZMW = 'ZMW', // Zambian Kwacha
    ZWL = 'ZWL', // Zimbabwean Dollar
}

export type StatusBadgeLabel = (typeof StatusLabel)[Status];

export interface ProductPrice {
    readonly id: string;
    currency: Currency;
    amount: string;
    status: {
        value: Status;
        badge?: {
            name: StatusBadgeLabel | null;
            variant: BadgeVariants['variant'];
        } | null;
        switch?: boolean | null;
    };
    product_id: Product['id'];
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductWithPrices extends Product {
    prices: ProductPrice[];
}
