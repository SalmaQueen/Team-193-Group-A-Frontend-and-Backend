package com.fareplanpassenger.myandroidphpapp;
/**Add content and layout inflater variables
 * Create a constructor to pass context through it
 * Create arrays to store the values for our slider
 * override instantiateItem
 * override destroyItem*/
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.constraintlayout.widget.ConstraintLayout;
import androidx.viewpager.widget.PagerAdapter;


public class SliderAdapter extends PagerAdapter {

    Context context;
    LayoutInflater layoutInflater;

    public SliderAdapter(Context context) { // constructor
        this.context = context;
    }

    //Arrays
    public int [] slide_images ={
            R.mipmap.register_round,
            R.mipmap.pay_round,
            R.mipmap.scan_round,
    };
    public String[] slide_headings ={
            "REGISTER",
            "PAY TO GENERATE QR CODE",
            "LET YOUR CODE GET SCANNED",
    };
    public  String[] side_desc = {
            "FarePlan helps you go cashless. After this tour, FarePlan will take you to a registration screen. Register your MPESA number and wait to receive a verification code. You can always change or edit your MPESA number through the settings tab on the home dashboard.",
            "On the main dashboard, select how you'd like to pay. Either via App, USSD (or dial *100200# on your phone) or Website. A QR code will automatically be generated after payment. If you have difficulties in paying, go to the settings tab on the dashboard to check if your MPESA number is correctly set up.",
            "The conductor will need to scan the generated code to approve your payment. In case the code is not generated, the conductor will ask for your set up MPESA phone number to approve your payment. \n\nFarePlan: www.fareplan.com\n\n\n Click finish to get started"
    };


 /**Add background array if you are using different backgrounds*/

    @Override
    public int getCount() {
        return slide_headings.length; //count is equal to number of heading
    }

    @Override
    public boolean isViewFromObject(@NonNull View view, @NonNull Object object) {
        return view == (ConstraintLayout) object; // change to constraint layout
    }

    @NonNull
    @Override
    //required to give those side effects and inflate all of these in this adapter
    public Object instantiateItem(@NonNull ViewGroup container, int position) {
            layoutInflater = (LayoutInflater) context.getSystemService(context.LAYOUT_INFLATER_SERVICE);
            View view = layoutInflater.inflate(R.layout.slide_layout,container,false);

            //initialize views
            ImageView slideImage = view.findViewById(R.id.imageIV);
            TextView slideHeading  = view.findViewById(R.id.headingTV);
            TextView slideDesc  = view.findViewById(R.id.descTV);

            //setting values
            slideImage.setImageResource(slide_images[position]); // the current position of side will pass to it and it will set image automatically
            slideHeading.setText(slide_headings[position]);
            slideDesc.setText(side_desc[position]);

            container.addView(view);
            return view;
    }

    @Override
    //when we reach last page it will then stop
    public void destroyItem(@NonNull ViewGroup container, int position, @NonNull Object object) {
        container.removeView((ConstraintLayout)object);
    }
}
