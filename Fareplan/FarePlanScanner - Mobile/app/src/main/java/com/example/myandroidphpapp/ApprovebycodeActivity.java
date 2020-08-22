package com.example.myandroidphpapp;

import androidx.appcompat.app.AppCompatActivity;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.res.Resources;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.graphics.Color;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.Toast;

import com.androidnetworking.AndroidNetworking;
import com.androidnetworking.common.Priority;
import com.androidnetworking.error.ANError;
import com.androidnetworking.interfaces.JSONObjectRequestListener;

import org.json.JSONException;
import org.json.JSONObject;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class ApprovebycodeActivity extends AppCompatActivity {
    private EditText editTextCode;
    Cursor cursor;
    String conductor_mobile;
    SweetAlertDialog confirm_approval;
    MediaPlayer scanner_sound,success_sound,failed_sound;
    Resources resources;
    String received_pay_code;
    SQLiteDatabase db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_approvebycode);
        editTextCode = findViewById(R.id.editTextCode);
        db= openOrCreateDatabase("user_phone_number", MODE_PRIVATE,null);
        db.execSQL("CREATE TABLE IF NOT EXISTS phonenumber(id VARCHAR,number VARCHAR)");
        AndroidNetworking.initialize(getApplicationContext());
        resources = getResources();

        cursor = db.rawQuery("SELECT * FROM phonenumber",null);
        if (cursor.getCount()==0){
            startActivity(new Intent(getApplicationContext(),OnboardingActivity.class));
            finish();
        }else {
            findViewById(R.id.buttonContinue).setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    cursor = db.rawQuery("SELECT * FROM phonenumber",null);
                    db.rawQuery("SELECT * FROM phonenumber ",null);
                    StringBuffer buffer = new StringBuffer();

                    while (cursor.moveToNext()){
                        buffer.append(cursor.getString(1));
                    }

                    conductor_mobile = buffer.toString().trim();


                    received_pay_code = editTextCode.getText().toString().trim();

                    if(received_pay_code.isEmpty() || received_pay_code.length() < 3){
                        editTextCode.setError("Enter a valid code");
                        editTextCode.requestFocus();
                        return;
                    }else {
                        confirm_approval = new SweetAlertDialog(ApprovebycodeActivity.this, SweetAlertDialog.WARNING_TYPE);
                        confirm_approval.setTitleText("Approving transaction")
                                .setContentText("Approve transaction with code "+ received_pay_code)
                                .setCancelText("Cancel")
                                .setConfirmText("Approve")
                                .showCancelButton(true)
                                .setCancelClickListener(new SweetAlertDialog.OnSweetClickListener() {
                                    @Override
                                    public void onClick(SweetAlertDialog sDialog) {
                                        sDialog.cancel();
                                    }
                                }).setConfirmClickListener(new SweetAlertDialog.OnSweetClickListener() {
                            @Override
                            public void onClick(SweetAlertDialog sweetAlertDialog) {
                                onpayment(received_pay_code);
                            }
                        }).show();
                    }
                }
            });
        }
    }
    @SuppressLint("StringFormatInvalid")
    public void onpayment(String pay_code){
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
        AndroidNetworking.post(String.format(resources.getString(R.string.approve_by_code),"?","&"))
                .addBodyParameter("pay_code",pay_code.trim())
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
                                editTextCode.setText("");
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
                        error_alert.setContentText("Something went wrong").show();
                    }
                });
    }
}
