// paranın rengini belirlemek için kullanılır
export function useAmountColorClass(amount: number) {
  if (amount < 0) {
    return 'text-negative'; // Apply negative color class
  } else if (amount > 0) {
    return 'text-positive'; // Apply positive color class
  } else {
    return ''; // Return empty string for zero (no color change)
  }
}
