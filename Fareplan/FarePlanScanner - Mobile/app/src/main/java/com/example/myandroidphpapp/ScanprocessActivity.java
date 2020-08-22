package com.example.myandroidphpapp;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.graphics.Color;
import android.media.MediaPlayer;
import android.os.Bundle;

import com.androidnetworking.AndroidNetworking;
import com.androidnetworking.common.Priority;
import com.androidnetworking.error.ANError;
import com.androidnetworking.interfaces.JSONObjectRequestListener;

import org.json.JSONException;
import org.json.JSONObject;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class ScanprocessActivity extends AppCompatActivity {
    SweetAlertDialog confirm_approval;
    String conductor_mobile,received_paying_mobile;
    StringBuilder paying_phone_number;
    MediaPlayer scanner_sound,success_sound,failed_sound;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_scanprocess);

        Intent intent = getIntent();
        conductor_mobile = intent.getStringExtra("conductor_mobile");
        received_paying_mobile = intent.getStringExtra("paying_mobile");

        paying_phone_number = new StringBuilder(received_paying_mobile);
        paying_phone_number.setCharAt(0,'4');
        paying_phone_number.toString().trim();




        confirm_approval = new SweetAlertDialog(ScanprocessActivity.this, SweetAlertDialog.WARNING_TYPE);
        confirm_approval.setTitleText("Approving transaction")
                .setContentText("Approve transaction with phone number "+"+25"+ paying_phone_number)
                .setCancelText("Cancel")
                .setConfirmText("Approve")
                .showCancelButton(true)
                .setCancelClickListener(new SweetAlertDialog.OnSweetClickListener() {
                    @Override
                    public void onClick(SweetAlertDialog sDialog) {
                        Intent intent = new Intent(getApplicationContext(),QuickscanActivity.class);
                        startActivity(intent);
                        intent.setFlags(Intent.FLAG_ACTIVITY_NO_HISTORY);
                        ScanprocessActivity.this.finish();
                    }
                }).setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
            @Override
            public void onClick(SweetAlertDialog sweetAlertDialog) {
                onpayment("25"+ paying_phone_number);
            }
        }).show();
    }


    public void onpayment(String paid_phone_number){
        //Loading alert
        final SweetAlertDialog loading = new SweetAlertDialog(this, SweetAlertDialog.PROGRESS_TYPE);
        loading.getProgressHelper().setBarColor(Color.parseColor("#A5DC86"));
        loading.setTitleText("Loading");
        loading.setContentText("Please wait...");
        loading.setCancelable(false);
        loading.show();
        scanner_sound = MediaPlayer.create(this, R.raw.scanner_sound);
        success_sound = MediaPlayer.create(this, R.raw.success_sound);
        failed_sound = MediaPlayer.create(this, R.raw.failed_sound);
        scanner_sound.start();

        //Message alert
        final SweetAlertDialog message_alert = new SweetAlertDialog(this, SweetAlertDialog.SUCCESS_TYPE);
        message_alert.setTitleText("PAID!").setCancelable(false);

        //Warning alert
        final  SweetAlertDialog error_alert = new SweetAlertDialog(this, SweetAlertDialog.ERROR_TYPE);
        error_alert.setTitleText("FAILED!")
                .setCancelable(false);
        AndroidNetworking.post("http://92138cd70042.ngrok.io/api/approve_by_phone")
                .addBodyParameter("paid_phone_number",paid_phone_number.trim())
                .addBodyParameter("conductor_mobile",conductor_mobile)
                .addHeaders("token", "1234")
                .setTag("test")
                .setPriority(Priority.HIGH)
                .build()
                .getAsJSONObject(new JSONObjectRequestListener() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {
                            loading.dismissWithAnimation();
                            scanner_sound.stop();
                            if (response.getString("value").equals("0")){
                                success_sound.start();
                                message_alert.setContentText(response.getString("message")).show();
                                confirm_approval.dismissWithAnimation();

                            }else {
                                failed_sound.start();
                                error_alert.setContentText(response.getString("message")).show();
                                confirm_approval.dismissWithAnimation();
                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                    @Override
                    public void onError(ANError anError) {
                        confirm_approval.dismissWithAnimation();
                        loading.dismiss();
                        scanner_sound.stop();
                        failed_sound.start();
                        error_alert.setContentText("CHECK YOUR DATA").show();
                    }
                });
    }
}
