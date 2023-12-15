export const formatMoney = (money, locale = 'nl', currency = 'EUR') =>
    new Intl.NumberFormat(locale, { style: 'currency', currency }).format(
        money / 100,
    )
