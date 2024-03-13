export const formatMoney = (money, locale = 'nl', currency = 'EUR') =>
    new Intl.NumberFormat(locale, { style: 'currency', currency }).format(
        money / 100,
    )

export const getMonthFromString = (mon) => {
    if (!mon) return null
    var d = Date.parse(mon + '1, 2012')
    if (!isNaN(d)) return new Date(d).getMonth()
    return -1
}
