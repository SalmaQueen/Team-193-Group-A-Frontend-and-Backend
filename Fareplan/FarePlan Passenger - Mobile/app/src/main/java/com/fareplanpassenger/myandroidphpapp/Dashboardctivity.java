package com.fareplanpassenger.myandroidphpapp;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import android.Manifest;
import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.content.res.Resources;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.net.Uri;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.GridView;

import java.util.ArrayList;

public class Dashboardctivity extends AppCompatActivity {
    SQLiteDatabase db;
    GridView mIcons;
    ArrayList<Item> icons;
    CustomAdapter adapter;
    Cursor cursor;
    Resources resources;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_dashboardctivity);
        resources = getResources();

        mIcons = findViewById(R.id.icons);
        icons = new ArrayList<>();
        adapter = new CustomAdapter(this,icons);

        Item item = new Item(R.mipmap.idea,"App");
        Item item2 = new Item(R.mipmap.suggestion,"Web");
        Item item3 = new Item(R.mipmap.career,"USSD");
        Item item4 = new Item(R.mipmap.medical,"Join FarePlan");
        Item item5 = new Item(R.mipmap.trash,"Payments");
        Item item6 = new Item(R.mipmap.settings,"Settings");

        icons.add(item);
        icons.add(item2);
        icons.add(item3);
        icons.add(item4);
        icons.add(item5);
        icons.add(item6);


        db= openOrCreateDatabase("user_phone_number", MODE_PRIVATE,null);
        db.execSQL("CREATE TABLE IF NOT EXISTS phonenumber(id VARCHAR,number VARCHAR)");

        cursor = db.rawQuery("SELECT * FROM phonenumber",null);
        if (cursor.getCount()==0){
            startActivity(new Intent(getApplicationContext(),OnboardingActivity.class));
            finish();
        }


        mIcons.setAdapter(adapter);
        mIcons.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @SuppressLint("StringFormatInvalid")
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                if (position==0){
                    Cursor cursor = db.rawQuery("SELECT * FROM phonenumber ",null);
                    StringBuffer buffer = new StringBuffer();

                    while (cursor.moveToNext()){
                        buffer.append(cursor.getString(1));
                    }

                    Intent intent = new Intent(getApplicationContext(),ApppaymentActivity.class);
                    intent.putExtra("mobile", buffer.toString().trim());
                    startActivity(intent);
                }else if (position==1){
                    Intent intent = new Intent(getApplicationContext(), WebActivity.class);
                    intent.putExtra("url", String.format(resources.getString(R.string.pay),"?","&"));
                    startActivity(intent);
                }else if (position==2){
                    Intent intent = new Intent(Intent.ACTION_CALL, Uri.parse("tel:" + "*384"+Uri.encode("#")));
                    if (ContextCompat.checkSelfPermission(Dashboardctivity.this,
                            Manifest.permission.CALL_PHONE) != PackageManager.PERMISSION_GRANTED) {
                        ActivityCompat.requestPermissions(Dashboardctivity.this, new String[]{Manifest.permission.CALL_PHONE},1);
                    }
                    else
                    {
                        startActivity(intent);
                    }
                }else if (position==3){
                    Intent intent = new Intent(getApplicationContext(), WebActivity.class);
                    intent.putExtra("url", String.format(resources.getString(R.string.contact),"?","&"));
                    startActivity(intent);
                }else if (position==4){
                    startActivity(new Intent(getApplicationContext(), PaymentsActivity.class));
                }else {
                    startActivity(new Intent(getApplicationContext(),SettingsActivity.class));
                }
            }
        });
    }
}
