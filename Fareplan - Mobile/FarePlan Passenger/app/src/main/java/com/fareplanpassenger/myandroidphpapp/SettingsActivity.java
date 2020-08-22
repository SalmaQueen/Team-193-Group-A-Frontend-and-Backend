package com.fareplanpassenger.myandroidphpapp;

import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.view.View;
import android.widget.TextView;

import androidx.appcompat.app.ActionBar;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.preference.PreferenceFragmentCompat;

public class SettingsActivity extends AppCompatActivity {
    TextView mEdtPhone;
    SQLiteDatabase db;
    AlertDialog.Builder builder,builder2;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.settings_activity);
        builder = new AlertDialog.Builder(this);
        builder2 = new AlertDialog.Builder(this);
        db= openOrCreateDatabase("user_phone_number", MODE_PRIVATE,null);
        db.execSQL("CREATE TABLE IF NOT EXISTS phonenumber(id VARCHAR,number VARCHAR)");

        mEdtPhone = findViewById(R.id.edit_phone);
        mEdtPhone.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                builder.setCancelable(false).setTitle("Editing phone number").setMessage("Are you sure you want to change your phone number?")
                        .setNegativeButton("Cancel", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {

                            }
                        })
                        .setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                                                Cursor cursor = db.rawQuery("SELECT * FROM phonenumber WHERE id = '"+1+"'", null);
                                if (cursor.moveToFirst()){
                                    db.execSQL("DELETE FROM phonenumber WHERE id = '"+1+"'");

                                    builder2.setCancelable(false).setTitle("SUCCESS").setMessage("Previous number has been removed. Proceed to register a new MPESA number.")

                                            .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                                                @Override
                                                public void onClick(DialogInterface dialog, int which) {
                                                    startActivity(new Intent(getApplicationContext(),RegistrationActivity.class));
                                                    finish();
                                                }
                                            }).create().show();
                                }else {
                                    startActivity(new Intent(getApplicationContext(),RegistrationActivity.class));
                                    finish();
                                }
                            }
                        }).create().show();
            }
        });
        getSupportFragmentManager()
                .beginTransaction()
                .replace(R.id.settings, new SettingsFragment())
                .commit();
        ActionBar actionBar = getSupportActionBar();
        if (actionBar != null) {
            actionBar.setDisplayHomeAsUpEnabled(true);
        }
    }

    public static class SettingsFragment extends PreferenceFragmentCompat {
        @Override
        public void onCreatePreferences(Bundle savedInstanceState, String rootKey) {
            setPreferencesFromResource(R.xml.root_preferences, rootKey);
        }
    }
}