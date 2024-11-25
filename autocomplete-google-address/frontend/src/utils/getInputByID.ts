export const getInputByID = (id: string): HTMLInputElement | null =>
  document.getElementById(id) as HTMLInputElement | null;
