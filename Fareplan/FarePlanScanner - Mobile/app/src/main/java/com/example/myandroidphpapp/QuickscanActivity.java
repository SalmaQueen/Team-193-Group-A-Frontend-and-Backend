package com.example.myandroidphpapp;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import android.Manifest;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.os.Bundle;
import android.util.SparseArray;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.vision.CameraSource;
import com.google.android.gms.vision.Detector;
import com.google.android.gms.vision.barcode.Barcode;
import com.google.android.gms.vision.barcode.BarcodeDetector;

import java.io.IOException;

import cn.pedant.SweetAlert.SweetAlertDialog;

public class QuickscanActivity extends AppCompatActivity {
    final int  PERMISSION_CODE = 1001;
    CameraSource cameraSource;
    BarcodeDetector detector;
    TextView textScanResult;
    SurfaceView cameraSurfaceView;
    String conductor_mobile;
    Cursor cursor;
    SQLiteDatabase db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_quickscan);
        textScanResult = findViewById(R.id.textScanResult);
        cameraSurfaceView = findViewById(R.id.cameraSurfaceView);
        db= openOrCreateDatabase("user_phone_number", MODE_PRIVATE,null);
        db.execSQL("CREATE TABLE IF NOT EXISTS phonenumber(id VARCHAR,number VARCHAR)");

        cursor = db.rawQuery("SELECT * FROM phonenumber",null);


        if (ContextCompat.checkSelfPermission(QuickscanActivity.this, Manifest.permission.CAMERA) == PackageManager.PERMISSION_GRANTED) {
            //Toast.makeText(QuickscanActivity.this, "Camera permission is already granted", Toast.LENGTH_SHORT).show();
            setupControls();
        } else {
            // Request Camera Permission
            ActivityCompat.requestPermissions(QuickscanActivity.this, new String[]{Manifest.permission.CAMERA}, PERMISSION_CODE);
        }
    }

    public void onpayment(String scanvalue){
        String type = "quickscan";
        Intent intent = getIntent();
        conductor_mobile = intent.getStringExtra("mobile");
        BackgroundWorker backgroundWorker = new  BackgroundWorker(getApplicationContext());
        backgroundWorker.execute(type,scanvalue,conductor_mobile);
        Toast.makeText(this, "Please wait", Toast.LENGTH_SHORT).show();
    }

    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        switch (requestCode) {
            case PERMISSION_CODE:
                // Check Camera permission is granted or not
                if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    Toast.makeText(QuickscanActivity.this, "Camera  permission granted", Toast.LENGTH_SHORT).show();
                    setupControls();
                } else {
                    Toast.makeText(QuickscanActivity.this, "Camera  permission denied", Toast.LENGTH_SHORT).show();
                }
                break;
        }
    }


    private void setupControls(){
        detector = new  BarcodeDetector.Builder(getApplicationContext()).build();
        cameraSource = new  CameraSource.Builder(getApplicationContext(),detector).setAutoFocusEnabled(true).build();
        cameraSurfaceView.getHolder().addCallback(surfaceCallBack);
        detector.setProcessor(processor);

    }

    SurfaceHolder.Callback surfaceCallBack = new SurfaceHolder.Callback() {
        @Override
        public void surfaceCreated(SurfaceHolder surfaceHolder) {
            try {
                cameraSource.start(surfaceHolder);

            } catch (IOException e) {
                e.printStackTrace();
            }
        }

        @Override
        public void surfaceChanged(SurfaceHolder holder, int format, int width, int height) {

        }

        @Override
        public void surfaceDestroyed(SurfaceHolder holder) {
            cameraSource.stop();
        }
    };

    Detector.Processor<Barcode> processor= new Detector.Processor<Barcode>() {
        @Override
        public void release() {

        }

        @Override
        public void receiveDetections(Detector.Detections<Barcode> detections) {
            if (detections != null && !detections.getDetectedItems().toString().isEmpty()){

                SparseArray<Barcode> qrCodes= detections.getDetectedItems();
                final Barcode code = qrCodes.valueAt(0);
//                textScanResult.setText("verifying "+code.displayValue);
                textScanResult.setText("Scan completed");

                Cursor cursor = db.rawQuery("SELECT * FROM phonenumber ",null);
                StringBuffer buffer = new StringBuffer();

                while (cursor.moveToNext()){
                    buffer.append(cursor.getString(1));
                }

                Intent intent = new Intent(getApplicationContext(), ScanprocessActivity.class);
                intent.putExtra("conductor_mobile", buffer.toString().trim());
                intent.putExtra("paying_mobile", code.displayValue.trim());
                intent.setFlags(Intent.FLAG_ACTIVITY_NO_HISTORY);
                startActivity(intent);
                QuickscanActivity.this.finish();


            }else{
                textScanResult.setText("Please focus on the code");
            }
        }
    };
}
