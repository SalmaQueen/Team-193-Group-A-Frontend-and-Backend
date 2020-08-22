package com.fareplanpassenger.myandroidphpapp;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import android.Manifest;
import android.content.pm.PackageManager;
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

public class ScancodeActivity extends AppCompatActivity {
    final int  PERMISSION_CODE = 1001;
    CameraSource cameraSource;
    BarcodeDetector detector;
    TextView textScanResult;
    SurfaceView cameraSurfaceView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_scancode);
        textScanResult = findViewById(R.id.textScanResult);
        cameraSurfaceView = findViewById(R.id.cameraSurfaceView);
        if (ContextCompat.checkSelfPermission(ScancodeActivity.this, Manifest.permission.CAMERA) == PackageManager.PERMISSION_GRANTED) {
            Toast.makeText(ScancodeActivity.this, "Camera permission is already granted", Toast.LENGTH_SHORT).show();
            setupControls();
        } else {
            // Request Camera Permission
            ActivityCompat.requestPermissions(ScancodeActivity.this, new String[]{Manifest.permission.CAMERA}, PERMISSION_CODE);
        }
    }

    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        switch (requestCode) {
            case PERMISSION_CODE:
                // Check Camera permission is granted or not
                if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    Toast.makeText(ScancodeActivity.this, "Camera  permission granted", Toast.LENGTH_SHORT).show();
                    setupControls();
                } else {
                    Toast.makeText(ScancodeActivity.this, "Camera  permission denied", Toast.LENGTH_SHORT).show();
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
                Barcode code = qrCodes.valueAt(0);
                textScanResult.setText(code.displayValue);

            }else{
                textScanResult.setText("");
            }
        }
    };
}
