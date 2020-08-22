package com.example.myandroidphpapp;


import android.app.AlertDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.view.WindowManager;
import android.widget.Toast;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;

public class BackgroundWorker extends AsyncTask<String,Void,String> {
    Context mContext;
    AlertDialog alertDialog;
    String login_url = "";

    public BackgroundWorker(Context context) {
        this.mContext = context;
    }

    @Override
    protected String doInBackground(String... params) {
        String type = params[0];
        //Either do it this way to connect to the localhost


        if (type.equals("approve_by_phone")){
            try {
                String paid_phone_number = params[1];
                String conductor_mobile = params[2];
//                login_url = "http://6f4f84fe7c8f.ngrok.io/approve/"+conductor_mobile+"/"+paid_phone_number;
                login_url = "http://dc9fdafeebf7.ngrok.io/projects/android_php/approve_by_phone.php";
                URL url = new URL(login_url);
                HttpURLConnection httpURLConnection = (HttpURLConnection)url.openConnection();
                httpURLConnection.setRequestMethod("POST");
                httpURLConnection.setDoOutput(true);
                httpURLConnection.setDoInput(true);

                //To send data to the server, use the OutputStream and the buffered writer
                OutputStream outputStream = httpURLConnection.getOutputStream();
                BufferedWriter bufferedWriter = new BufferedWriter(new OutputStreamWriter(outputStream,"UTF-8"));
                String post_data = URLEncoder.encode("paid_phone_number","UTF-8")+"="+URLEncoder.encode(paid_phone_number,"UTF-8")+"&"+
                        URLEncoder.encode("conductor_mobile","UTF-8")+"="+URLEncoder.encode(conductor_mobile,"UTF-8");
                bufferedWriter.write(post_data);
                bufferedWriter.flush();
                bufferedWriter.close();
                outputStream.close();
                //Once you post some data to the server, you expect some request
                //To receive the response, use the InputStream and the buffered reader
                InputStream inputStream = httpURLConnection.getInputStream();
                BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(inputStream,"iso-8859-1"));
                String result = "";
                String line = "";
                while ((line = bufferedReader.readLine()) != null){
                    result += line;
                }
                bufferedReader.close();
                inputStream.close();
                httpURLConnection.disconnect();
                return result;

            } catch (MalformedURLException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
        return null;
    }

    @Override
    protected void onPreExecute() {
        alertDialog = new AlertDialog.Builder(mContext).create();
        alertDialog.setTitle("Login Status");
    }

    @Override
    protected void onPostExecute(String result) {

        //show dialog
        if (result==null){
            alertDialog.setMessage("No result found");
            alertDialog.show();
//            Toast.makeText(mContext, "Null", Toast.LENGTH_SHORT).show();
        }else {
            alertDialog.setMessage(result);
//            Toast.makeText(mContext, result, Toast.LENGTH_SHORT).show();
        }
    }

    @Override
    protected void onProgressUpdate(Void... values) {
        super.onProgressUpdate(values);
    }

}