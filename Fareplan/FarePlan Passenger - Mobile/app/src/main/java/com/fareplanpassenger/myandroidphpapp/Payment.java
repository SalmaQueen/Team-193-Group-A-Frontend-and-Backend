package com.fareplanpassenger.myandroidphpapp;

public class Payment {
    String sacco_name,vehicle_registration_number;

    public Payment(String sacco_name, String vehicle_registration_number) {
        this.sacco_name = sacco_name;
        this.vehicle_registration_number = vehicle_registration_number;
    }

    public String getSacco_name() {
        return sacco_name;
    }

    public void setSacco_name(String sacco_name) {
        this.sacco_name = sacco_name;
    }

    public String getVehicle_registration_number() {
        return vehicle_registration_number;
    }

    public void setVehicle_registration_number(String vehicle_registration_number) {
        this.vehicle_registration_number = vehicle_registration_number;
    }
}
