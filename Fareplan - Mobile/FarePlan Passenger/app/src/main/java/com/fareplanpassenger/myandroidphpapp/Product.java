package com.fareplanpassenger.myandroidphpapp;
public class Product {
    private int id;
    private String is_approved;
    private String MpesaReceiptNumber;
    private String Amount;
    private String PhoneNumber;
    private String TransactionDate;
    private String sacco_name;
    private String vehicle_registration_number;

    public Product(int id, String is_approved, String mpesaReceiptNumber, String amount, String phoneNumber, String transactionDate, String sacco_name, String vehicle_registration_number) {
        this.id = id;
        this.is_approved = is_approved;
        MpesaReceiptNumber = mpesaReceiptNumber;
        Amount = amount;
        PhoneNumber = phoneNumber;
        TransactionDate = transactionDate;
        this.sacco_name = sacco_name;
        this.vehicle_registration_number = vehicle_registration_number;
    }

    public int getId() {
        return id;
    }

    public void setId(int id) {
        this.id = id;
    }

    public String getIs_approved() {
        return is_approved;
    }

    public void setIs_approved(String is_approved) {
        this.is_approved = is_approved;
    }

    public String getMpesaReceiptNumber() {
        return MpesaReceiptNumber;
    }

    public void setMpesaReceiptNumber(String mpesaReceiptNumber) {
        MpesaReceiptNumber = mpesaReceiptNumber;
    }

    public String getAmount() {
        return Amount;
    }

    public void setAmount(String amount) {
        Amount = amount;
    }

    public String getPhoneNumber() {
        return PhoneNumber;
    }

    public void setPhoneNumber(String phoneNumber) {
        PhoneNumber = phoneNumber;
    }

    public String getTransactionDate() {
        return TransactionDate;
    }

    public void setTransactionDate(String transactionDate) {
        TransactionDate = transactionDate;
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