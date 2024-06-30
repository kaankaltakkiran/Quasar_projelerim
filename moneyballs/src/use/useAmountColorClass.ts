// paranın rengini belirlemek için kullanılır
export function useAmountColorClass(amount: number) {
  if (amount < 0) {
    return 'text-negative';
  } else if (amount > 0) {
    return 'text-positive';
  } else {
    return 'text-grey-6';
  }
}
