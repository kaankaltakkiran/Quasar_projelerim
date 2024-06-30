// Para birimi formatlama fonksiyonu
export function useCurrencify(amount: number) {
  const posNegSymbol = amount < 0 ? '-' : '+';
  const currencySymbol = '$';
  const amountPositive = Math.abs(amount);
  const amountFormatted = amountPositive.toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
  return `${posNegSymbol} ${currencySymbol} ${amountFormatted}`;
}
