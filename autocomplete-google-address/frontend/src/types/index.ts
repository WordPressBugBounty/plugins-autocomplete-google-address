export interface AddressConfiguration {
  id: number | null | undefined; // Allow null or undefined
  name: string;
  street_address_id: string;
  city_id: string;
  state_id: string;
  zip_id: string;
  country_id: string;
  sub_locality: string;
  county: string;
  latitude: string;
  longitude: string;
  country_restriction: string;
  country_type: "short" | "long";
  state_type: "short" | "long";
  address_type: "short" | "long";
  show_map: boolean;
  optional_1: string;
  optional_2: string;
  search_type: string;
  type: "regular" | "woocommerce";
  map_width: string;
  map_height: string;
  map_display_id: string;
  zoom_level: string;
}

// export interface FormSetup {
//   single_line_address: string;
//   street_address: string;
//   city: string;
//   sub_locality: string;
//   state: string;
//   zip_code: string;
//   county: string;
//   country: string;
//   latitude: string;
//   longitude: string;
//   country_restriction: string;
//   country_type: string;
//   state_type: string;
//   administrative_area_level_3: string;
//   administrative_area_level_4: string;
//   search_type: string;
//   street_address_format: "number_first" | "route_first";
// }

export interface ComponentRestrictions {
  country: string[];
}

export interface AutocompleteOptions {
  types: string[];
  fields: string[];
  componentRestrictions: ComponentRestrictions;
}