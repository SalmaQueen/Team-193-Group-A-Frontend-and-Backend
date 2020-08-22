package com.fareplanpassenger.myandroidphpapp;

import android.annotation.SuppressLint;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Resources;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.widget.EditText;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.androidnetworking.AndroidNetworking;
import com.androidnetworking.common.Priority;
import com.androidnetworking.error.ANError;
import com.androidnetworking.interfaces.JSONObjectRequestListener;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class ApppaymentActivity extends AppCompatActivity implements PaymentAdapter.OnItemClickListener {
    private static String load_vehicles_url;
    List<Payment> productList;
    RecyclerView recyclerView;
    PaymentAdapter adapter;
    SwipeRefreshLayout pullToRefresh;
    AlertDialog.Builder alertDialog;
    EditText editTextAmount;
    String mobile;
    Cursor cursor;
    SQLiteDatabase db;
    String sacco_name,vehicle_registration_number;
    SweetAlertDialog pDialog,error_alert;
    Resources resources;
    EditText mEdtAccount;
    @SuppressLint("StringFormatInvalid")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_apppayment);
        AndroidNetworking.initialize(getApplicationContext());

        db= openOrCreateDatabase("user_phone_number", MODE_PRIVATE,null);
        db.execSQL("CREATE TABLE IF NOT EXISTS phonenumber(id VARCHAR,number VARCHAR)");

        cursor = db.rawQuery("SELECT * FROM phonenumber",null);
        if (cursor.getCount()==0){
            startActivity(new Intent(getApplicationContext(),RegistrationActivity.class));
            finish();
        }
        mEdtAccount = findViewById(R.id.editTextAccount);

        pDialog = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        pDialog.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
        pDialog.setTitleText("Loading accounts");
        pDialog.setContentText("Please wait...");
        pDialog.setCancelable(false);

        recyclerView = findViewById(R.id.recylcerView);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        productList = new ArrayList<>();

//        pullToRefresh = (SwipeRefreshLayout) findViewById(R.id.pullToRefresh);
        resources = getResources();
        load_vehicles_url = String.format(resources.getString(R.string.load_vehicles),"?","&");

        adapter = new PaymentAdapter(ApppaymentActivity.this, productList);
        load_vehicles();
        mEdtAccount.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {
                filter(s.toString().toUpperCase());
            }

            @Override
            public void afterTextChanged(Editable s) {
                filter(s.toString().toUpperCase());
            }
        });
    }

    void filter(String text){
        List<Payment> temp = new ArrayList();
        for(Payment d: productList){
            if(d.getVehicle_registration_number().contains(text)){
                temp.add(d);
            }
        }
        //update recyclerview
        adapter.setSearchOperation(temp);
    }

    private void load_vehicles() {
//        pullToRefresh.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
//
//            @Override
//            public void onRefresh() {
//                pDialog.dismissWithAnimation();
//                load_vehicles();
//                pullToRefresh.setRefreshing(false);
//            }
//        });
//        progressBar.setVisibility(View.VISIBLE);
        pDialog.show();
        StringRequest stringRequest = new StringRequest(Request.Method.POST, load_vehicles_url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            JSONArray array = new JSONArray(response);

                            productList.clear();
                            for (int i = 0; i < array.length(); i++) {
                                JSONObject product = array.getJSONObject(i);
                                productList.add(new Payment(
                                        product.getString("sacco_name"),
                                        product.getString("vehicle_registration_number")
                                ));
                            }


                            Collections.reverse(productList);
                            adapter.notifyDataSetChanged();
                            recyclerView.setAdapter(adapter);
//                            progressBar.setVisibility(View.INVISIBLE);
                            pDialog.dismissWithAnimation();

                            adapter.setOnItemClickListener(ApppaymentActivity.this);

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {
                        pDialog.dismissWithAnimation();
                        error_alert = new SweetAlertDialog(ApppaymentActivity.this, SweetAlertDialog.ERROR_TYPE);
                        error_alert.setTitleText("ERROR!")
                                .setContentText("Something went wrong!")
                                .show();
                    }
                });
        Volley.newRequestQueue(this).add(stringRequest);
    }
    public void onItemClick(int position) {
        alertDialog = new AlertDialog.Builder(this);
        alertDialog.setTitle("Payment status");
        editTextAmount = findViewById(R.id.editTextAmount);
        Intent intent = getIntent();
        mobile = intent.getStringExtra("mobile");

        final String amount = editTextAmount.getText().toString().trim();
        sacco_name = productList.get(position).getSacco_name();
        vehicle_registration_number = productList.get(position).getVehicle_registration_number();
        if (amount.trim().isEmpty()){
            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setCancelable(false);
            builder.setTitle("Empty field");
            builder.setMessage("Please enter amount");
            builder.setPositiveButton("OK", new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {

                }
            }).create().show();
            editTextAmount.setError("Please enter amount");
        }else {
            AlertDialog.Builder builder = new AlertDialog.Builder(this);
            builder.setCancelable(false);
            builder.setTitle("Paying");
            builder.setMessage("Pay Ksh "+amount+" to "+sacco_name+" account "+vehicle_registration_number);
            builder.setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {

                }
            });
            builder.setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    onpayment(mobile,amount,sacco_name,vehicle_registration_number);
                }
            }).create().show();

        }

    }


    public void onVerifyClick(int position) {
        Toast.makeText(this, "Verify transaction: "+position, Toast.LENGTH_SHORT).show();
    }

    public void onDeleteClick(int position) {
        Toast.makeText(this, "Deleted "+position, Toast.LENGTH_SHORT).show();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
//        Toast.makeText(this, "Destroyed", Toast.LENGTH_SHORT).show();
    }

    @SuppressLint("StringFormatInvalid")
    public void onpayment(String phone, String amount, String sacco_name, String vehicle_registration_number){
        //Loading alert
        final SweetAlertDialog loading = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        loading.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
        loading.setTitleText("Loading");
        loading.setContentText("Please wait...");
        loading.setCancelable(false);
        loading.show();

        //Message alert
        final SweetAlertDialog message_alert = new SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE);
        message_alert.setTitleText("SUCCESS!").setCancelable(false);

        //Warning alert
        final  SweetAlertDialog error_alert = new SweetAlertDialog(this, SweetAlertDialog.ERROR_TYPE);
        error_alert.setTitleText("FAILED!")
                .setCancelable(false);
        AndroidNetworking.post(String.format(resources.getString(R.string.pay_by_phone),"?","&"))
                .addBodyParameter("PhoneNumber",phone.trim())
                .addBodyParameter("vehicle_registration_number",vehicle_registration_number)
                .addBodyParameter("Amount",amount)
                .addBodyParameter("sacco_name",sacco_name)
                .addBodyParameter("pay_code","0")
                .addHeaders("token", "1234")
                .setTag("test")
                .setPriority(Priority.HIGH)
                .build()
                .getAsJSONObject(new JSONObjectRequestListener() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            loading.dismissWithAnimation();
                            if (response.getString("value").equals("0")){
                                message_alert.setTitleText("CODE: "+response.getString("pay_code")).setContentText(response.getString("message")).show();
                            }else {
                                error_alert.setContentText(response.getString("message")).show();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                    @Override
                    public void onError(ANError anError) {
                        loading.dismiss();
                        error_alert.setContentText(anError+"").show();
                    }
                });
    }

    @Override
    public void onPointerCaptureChanged(boolean hasCapture) {

    }
}