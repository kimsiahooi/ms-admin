import type { Machine } from '@/types/Tenant/machines';

export interface Product {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    is_active: boolean;
    is_active_display?: string | null;
    shelf_life_duration: string | null;
    shelf_life_type: 'SECOND' | 'MINUTE' | 'HOUR' | 'DAY' | 'MONTH' | 'YEAR' | null;
    shelf_life_type_display?: string | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductPrice {
    readonly id: string;
    currency:
        | 'AED'
        | 'AFN'
        | 'ALL'
        | 'AMD'
        | 'ANG'
        | 'AOA'
        | 'ARS'
        | 'AUD'
        | 'AWG'
        | 'AZN'
        | 'BAM'
        | 'BBD'
        | 'BDT'
        | 'BGN'
        | 'BHD'
        | 'BIF'
        | 'BMD'
        | 'BND'
        | 'BOB'
        | 'BRL'
        | 'BSD'
        | 'BTN'
        | 'BWP'
        | 'BYN'
        | 'BZD'
        | 'CAD'
        | 'CDF'
        | 'CHF'
        | 'CLP'
        | 'CNY'
        | 'COP'
        | 'CRC'
        | 'CUP'
        | 'CVE'
        | 'CZK'
        | 'DJF'
        | 'DKK'
        | 'DOP'
        | 'DZD'
        | 'EGP'
        | 'ERN'
        | 'ETB'
        | 'EUR'
        | 'FJD'
        | 'FKP'
        | 'FOK'
        | 'GBP'
        | 'GEL'
        | 'GGP'
        | 'GHS'
        | 'GIP'
        | 'GMD'
        | 'GNF'
        | 'GTQ'
        | 'GYD'
        | 'HKD'
        | 'HNL'
        | 'HRK'
        | 'HTG'
        | 'HUF'
        | 'IDR'
        | 'ILS'
        | 'IMP'
        | 'INR'
        | 'IQD'
        | 'IRR'
        | 'ISK'
        | 'JEP'
        | 'JMD'
        | 'JOD'
        | 'JPY'
        | 'KES'
        | 'KGS'
        | 'KHR'
        | 'KID'
        | 'KMF'
        | 'KRW'
        | 'KWD'
        | 'KYD'
        | 'KZT'
        | 'LAK'
        | 'LBP'
        | 'LKR'
        | 'LRD'
        | 'LSL'
        | 'LYD'
        | 'MAD'
        | 'MDL'
        | 'MGA'
        | 'MKD'
        | 'MMK'
        | 'MNT'
        | 'MOP'
        | 'MRU'
        | 'MUR'
        | 'MVR'
        | 'MWK'
        | 'MXN'
        | 'MYR'
        | 'MZN'
        | 'NAD'
        | 'NGN'
        | 'NIO'
        | 'NOK'
        | 'NPR'
        | 'NZD'
        | 'OMR'
        | 'PAB'
        | 'PEN'
        | 'PGK'
        | 'PHP'
        | 'PKR'
        | 'PLN'
        | 'PYG'
        | 'QAR'
        | 'RON'
        | 'RSD'
        | 'RUB'
        | 'RWF'
        | 'SAR'
        | 'SBD'
        | 'SCR'
        | 'SDG'
        | 'SEK'
        | 'SGD'
        | 'SHP'
        | 'SLE'
        | 'SLL'
        | 'SOS'
        | 'SRD'
        | 'SSP'
        | 'STN'
        | 'SYP'
        | 'SZL'
        | 'THB'
        | 'TJS'
        | 'TMT'
        | 'TND'
        | 'TOP'
        | 'TRY'
        | 'TTD'
        | 'TVD'
        | 'TWD'
        | 'TZS'
        | 'UAH'
        | 'UGX'
        | 'USD'
        | 'UYU'
        | 'UZS'
        | 'VES'
        | 'VND'
        | 'VUV'
        | 'WST'
        | 'XAF'
        | 'XCD'
        | 'XOF'
        | 'XPF'
        | 'YER'
        | 'ZAR'
        | 'ZMW'
        | 'ZWL';
    amount: string;
    is_active: boolean;
    is_active_display?: string | null;
    product_id: Product['id'];
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductWithPrices extends Product {
    prices: ProductPrice[];
}

export interface ProductPreset {
    readonly id: string;
    product_id: string;
    machine_id: string;
    name: string;
    description: string | null;
    cavity_quantity: string;
    cavity_type: 'PCS' | 'KILOGRAM' | 'GRAM';
    cycle_time: string;
    cycle_time_type: 'MILLISECOND' | 'SECOND' | 'MINUTE' | 'HOUR' | 'DAY' | 'MONTH' | 'YEAR';
    cycle_time_type_display?: string | null;
    is_active: boolean;
    is_active_display?: string | null;
    cavity_type_display?: string | null;
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductPresetWithProduct extends ProductPreset {
    product: Product | null;
}

export interface ProductPresetWithMachine extends ProductPreset {
    machine: Machine | null;
}

export interface ProductPresetWithProductAndMachine extends ProductPreset {
    product: Product | null;
    machine: Machine | null;
}

export interface ProductBom extends Product {
    readonly id: string;
    name: string;
    code: string;
    description: string | null;
    is_active: boolean;
    product_id: Product['id'];
    created_at: Date | null;
    updated_at: Date | null;
    deleted_at: Date | null;
}

export interface ProductBomWithProduct extends ProductBom {
    product: Product | null;
}
