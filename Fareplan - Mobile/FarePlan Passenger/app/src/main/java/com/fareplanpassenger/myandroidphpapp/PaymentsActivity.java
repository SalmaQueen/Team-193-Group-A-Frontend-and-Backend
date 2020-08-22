package com.fareplanpassenger.myandroidphpapp;
import android.annotation.SuppressLint;
import android.content.Intent;
import android.content.res.Resources;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;
import com.android.volley.Request;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

public class PaymentsActivity extends AppCompatActivity implements TransactionAdapter.OnItemClickListener {
    //this is the JSON Data URL
    //make sure you are using the correct ip else it will not work
    private static  String load_payments_url;
    //a list to store all the products
    List<Product> productList;
    //the recyclerview
    RecyclerView recyclerView;
    Button mBtnGenerateCode, mBtnScanCode,mBtnPay;
    TransactionAdapter adapter;
    SwipeRefreshLayout pullToRefresh;
    ProgressBar progressBar;
    Resources resources;

    @SuppressLint("StringFormatInvalid")
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_payments);
        progressBar = findViewById(R.id.progressBar);
        //getting the recyclerview from xml
        recyclerView = findViewById(R.id.recylcerView);
        recyclerView.setHasFixedSize(true);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        //initializing the productlist
        productList = new ArrayList<>();

        //this method will fetch and parse json
        //to display it in recyclerview
        pullToRefresh = (SwipeRefreshLayout) findViewById(R.id.pullToRefresh);
        resources = getResources();
        load_payments_url = String.format(resources.getString(R.string.load_payments),"?","&");
        load_payments();
    }

    private void load_payments() {
        pullToRefresh.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {

            @Override
            public void onRefresh() {
                //Here you can update your data from internet or from local SQLite data
                load_payments();
                pullToRefresh.setRefreshing(false);
//                Toast.makeText(MainActivity.this, "Refreshed", Toast.LENGTH_SHORT).show();
            }
        });
        progressBar.setVisibility(View.VISIBLE);
        StringRequest stringRequest = new StringRequest(Request.Method.POST, load_payments_url,
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            //converting the string to json array object
                            JSONArray array = new JSONArray(response);

                            //traversing through all the object
                            productList.clear();
                            for (int i = 0; i < array.length(); i++) {

                                //getting product object from json array
                                JSONObject product = array.getJSONObject(i);

                                //adding the product to product list
                                productList.add(new Product(
                                        product.getInt("id"),
                                        product.getString("is_approved"),
                                        product.getString("MpesaReceiptNumber"),
                                        product.getString("Amount"),
                                        product.getString("PhoneNumber"),
                                        product.getString("TransactionDate"),
                                        product.getString("sacco_name"),
                                        product.getString("vehicle_registration_number")
                                ));
                            }

                            //creating adapter object and setting it to recyclerview
                            adapter = new TransactionAdapter(PaymentsActivity.this, productList);

                            Collections.reverse(productList);
                            adapter.notifyDataSetChanged();
                            recyclerView.setAdapter(adapter);
                            progressBar.setVisibility(View.INVISIBLE);

                            adapter.setOnItemClickListener(PaymentsActivity.this);

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {

                    }
                });

        //adding our string request to queue
        Volley.newRequestQueue(this).add(stringRequest);
    }
    public void onItemClick(int position) {
        Intent intent = new Intent(getApplicationContext(),DetailsActivity.class);
        intent.putExtra("code", productList.get(position).getMpesaReceiptNumber());
        intent.putExtra("amount", productList.get(position).getAmount());
        startActivity(intent);
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

}