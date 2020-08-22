package com.fareplanpassenger.myandroidphpapp;

import androidx.appcompat.app.AppCompatActivity;

import android.graphics.Bitmap;
import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;

import com.google.zxing.BarcodeFormat;
import com.google.zxing.WriterException;
import com.journeyapps.barcodescanner.BarcodeEncoder;

public class DetailsActivity extends AppCompatActivity {
    TextView mMpesaReceipt,mAmount;
    ImageView mImgBarcode;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_details);
        mMpesaReceipt = findViewById(R.id.mTvCode);
        mAmount = findViewById(R.id.mTvAmount);
        mImgBarcode = findViewById(R.id.mImgBarcode);
        String code = getIntent().getStringExtra("code");
        String amount = getIntent().getStringExtra("amount");
        mMpesaReceipt.setText(code);
        mAmount.setText(amount);

        try {
            BarcodeEncoder encoder = new BarcodeEncoder();
            Bitmap bitmap;
            bitmap = encoder.encodeBitmap(code,
                    BarcodeFormat.QR_CODE, 500, 500);
            mImgBarcode.setImageBitmap(bitmap);
        } catch (WriterException e) {
            e.printStackTrace();
        }
    }
}
