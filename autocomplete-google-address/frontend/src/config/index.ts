import { ComponentRestrictions } from "../types/index";
import { generateOptions } from "../utils/generateOptions";
import { getInputByID } from "../utils/getInputByID";

export function parseConfig(input: any) {
  const countryArray = input.country_restriction
    .split(",")
    .map((country: string) => country.trim().toLowerCase());
  const countryList: ComponentRestrictions = { country: countryArray };
  const options = generateOptions(["address"], countryList);

  const streetAddressInput = getInputByID(input.street_address_id);

  return { options, streetAddressInput };
}
