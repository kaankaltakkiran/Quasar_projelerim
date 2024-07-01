// Para birimi formatlama fonksiyonu
import { LocalStorage } from 'quasar';

export function useCurrencify(amount: number) {
  const posNegSymbol = amount < 0 ? '-' : '+'; //pozitif veya negatif işaret
  const currencySymbol = LocalStorage.getItem('Currency') || '$'; // LocalStorage'dan currencySymbol al
  const amountPositive = Math.abs(amount); //negatif sayıları pozitife çevirme
  const amountFormatted = amountPositive.toLocaleString('en-US', {
    minimumFractionDigits: 2, //minimum 2 basamak
    maximumFractionDigits: 2, //maksimum 2 basamak
  });
  return `${posNegSymbol} ${currencySymbol} ${amountFormatted}`; //formatlanmış para birimi
}
