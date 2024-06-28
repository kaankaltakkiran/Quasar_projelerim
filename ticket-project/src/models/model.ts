export interface IPerson {
  id: number;
  user_name: string;
  user_email: string;
  user_status: string;
}

export interface IStation {
  id: string;
  name: string;
  targets: number[];
}

export interface ITravelİnfo {
  companyName: string;
  departureStation: string;
  arrivalStation: string;
  passengerCount: string;
  selectedDate: string;
}
