package com.example.myandroidphpapp;
/**
 * If we want different backgrounds for different pages - > create different slide_layouts
 * Create slider Adapter
 * Initialize the sliderAdapter
 * Create dot indicator
 * Intilize button and button function and logic
 */

import android.content.Intent;
import android.os.Bundle;
import android.text.Html;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;
import androidx.viewpager.widget.ViewPager;

public class OnboardingActivity extends AppCompatActivity {

    private ViewPager mSlideViewpager;
    private LinearLayout mDotLayout;

    private TextView mDots[]; // for dots

    private SliderAdapter sliderAdapter;

    private Button mPrev, mNext;

    private int mCurrentPage;

    //Indicates the dot change in color according to the pages
    ViewPager.OnPageChangeListener viewListener = new ViewPager.OnPageChangeListener() {
        @Override
        public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {

        }

        @Override
        public void onPageSelected(int position) {
            addDotIndicator(position);
            mCurrentPage = position;

            //logic inside the next and prev buttons
            if (position == 0) {
//                mNext.setEnabled(true);
                mPrev.setEnabled(false);
//                mPrev.setVisibility(View.INVISIBLE);
                mNext.setText("Next");
                mPrev.setText("");
            } else if (position == mDots.length - 1) { /** logic inside finish button this is an important part*/
//                mNext.setEnabled(true);
                mPrev.setEnabled(true);
                mPrev.setVisibility(View.VISIBLE);
                mNext.setText("Finish");
                mPrev.setText("Back");
                mNext.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
//                        startActivity(new Intent(getApplicationContext(),Dashboardctivity.class));
                        startActivity(new Intent(getApplicationContext(),RegistrationActivity.class));
                        finish();
                    }
                });
            } else {
//                mNext.setEnabled(true);
                mPrev.setEnabled(true);
                mPrev.setVisibility(View.VISIBLE);
                mNext.setText("Next");
                mPrev.setText("Back");
            }
        }

        @Override
        public void onPageScrollStateChanged(int state) {

        }
    };

    // Adding dots
    public void addDotIndicator(int position) {
        mDots = new TextView[3];

        mDotLayout.removeAllViews(); // fixes dots on all page

        for (int i = 0; i < mDots.length; i++) {
            mDots[i] = new TextView(this);
            mDots[i].setText(Html.fromHtml("&#8226;"));
            mDots[i].setTextSize(35);
            mDots[i].setTextColor(getResources().getColor(R.color.colorTransparentWhite));
            mDotLayout.addView(mDots[i]);
        }

        if (mDots.length > 0) {
            mDots[position].setTextColor(getResources().getColor(R.color.colorWhite));
        }
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_onboarding);

        mSlideViewpager = findViewById(R.id.mainVP);
        mDotLayout = findViewById(R.id.dotsLL);
        mNext = findViewById(R.id.nextBtn);
        mPrev = findViewById(R.id.previousBtn);

        sliderAdapter = new SliderAdapter(this);
        mSlideViewpager.setAdapter(sliderAdapter);
        addDotIndicator(0);

        mSlideViewpager.addOnPageChangeListener(viewListener); //adding on change page listener to our page
    }

    // Button click logic
    public void prev(View view) {
        mSlideViewpager.setCurrentItem(mCurrentPage - 1);
    }

    public void next(View view) {
        mSlideViewpager.setCurrentItem(mCurrentPage + 1);
    }
}
