package com.fareplanpassenger.myandroidphpapp;

import androidx.appcompat.app.AppCompatActivity;

import android.graphics.Bitmap;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;

import com.google.zxing.BarcodeFormat;
import com.google.zxing.WriterException;
import com.journeyapps.barcodescanner.BarcodeEncoder;

public class GeneratecodeActivity extends AppCompatActivity {
    Button mBtnGenerate;
    EditText mEdtText;
    ImageView mImgBarcode;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_generatecode);
        mBtnGenerate = findViewById(R.id.mBtnGenerate);
        mEdtText = findViewById(R.id.mEdtText);
        mImgBarcode = findViewById(R.id.mImgBarcode);
        mBtnGenerate.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                try {
                    BarcodeEncoder encoder = new BarcodeEncoder();
                    Bitmap bitmap;
                    bitmap = encoder.encodeBitmap(mEdtText.getText().toString(),
                            BarcodeFormat.QR_CODE, 500, 500);
                    mImgBarcode.setImageBitmap(bitmap);
                } catch (WriterException e) {
                    e.printStackTrace();
                }
            }
        });
    }
}
