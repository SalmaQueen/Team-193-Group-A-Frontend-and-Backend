package com.fareplanpassenger.myandroidphpapp;


import android.content.Context;
import android.graphics.Bitmap;
import android.view.ContextMenu;
        import android.view.LayoutInflater;
        import android.view.Menu;
        import android.view.MenuItem;
        import android.view.View;
        import android.view.ViewGroup;
        import android.widget.ImageView;
        import android.widget.TextView;


import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.google.zxing.BarcodeFormat;
import com.google.zxing.WriterException;
import com.journeyapps.barcodescanner.BarcodeEncoder;

import java.util.ArrayList;
        import java.util.List;

public class TransactionAdapter extends RecyclerView.Adapter<TransactionAdapter.ImageViewHolder> {
    private Context mContext;
    private List<Product> mUploads;
    private OnItemClickListener mListener;

    public TransactionAdapter(Context mContext, List<Product> mUploads) {
        this.mContext = mContext;
        this.mUploads = mUploads;
    }

    @Override
    public ImageViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(mContext).inflate(R.layout.transaction_list,parent,false);
        return new ImageViewHolder(v);
    }

    @Override
    public void onBindViewHolder(ImageViewHolder holder, int position) {
        Product uploadCurrent = mUploads.get(position);
        //loading the image
        //Generate code
        try {
            BarcodeEncoder encoder = new BarcodeEncoder();
            Bitmap bitmap;
            bitmap = encoder.encodeBitmap(uploadCurrent.getPhoneNumber(),
                    BarcodeFormat.QR_CODE, 500, 500);
            Glide.with(mContext)
                    .load(bitmap)
                    .into(holder.imageView);
        } catch (WriterException e) {
            e.printStackTrace();
        }


        holder.textViewAmount.setText(uploadCurrent.getAmount());
        holder.textViewReceiptNumber.setText(uploadCurrent.getMpesaReceiptNumber());
        holder.textViewPhoneNumber.setText(String.valueOf(uploadCurrent.getPhoneNumber()));
        holder.textViewTransactionDate.setText(String.valueOf(uploadCurrent.getTransactionDate()));

    }

    @Override
    public int getItemCount() {
        return mUploads.size();
    }

    public class ImageViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener, View.OnCreateContextMenuListener, MenuItem.OnMenuItemClickListener {

        TextView textViewAmount, textViewReceiptNumber, textViewPhoneNumber, textViewTransactionDate;
        ImageView imageView;
        public ImageViewHolder(View itemView) {
            super(itemView);
            textViewAmount = itemView.findViewById(R.id.textViewAmount);
            textViewReceiptNumber = itemView.findViewById(R.id.textViewReceiptNumber);
            textViewPhoneNumber = itemView.findViewById(R.id.textViewPhoneNumber);
            textViewTransactionDate = itemView.findViewById(R.id.textViewTransactionDate);
            imageView = itemView.findViewById(R.id.imgQrCode);

            itemView.setOnClickListener(this);
            itemView.setOnCreateContextMenuListener(this);

        }

        @Override
        public void onClick(View v) {
            if (mListener!=null){
                //Get the position of the clicked item
                int position = getAdapterPosition();
                if (position!=RecyclerView.NO_POSITION){
                    mListener.onItemClick(position);
                }
            }
        }
        // Handle Menu Items
        @Override
        public void onCreateContextMenu(ContextMenu menu, View v, ContextMenu.ContextMenuInfo menuInfo) {
            menu.setHeaderTitle("Select Action");
            MenuItem doWhatever = menu.add(Menu.NONE, 1, 1,"Verify code");
            MenuItem delete = menu.add(Menu.NONE,2,2,"Delete");
            doWhatever.setOnMenuItemClickListener(this);
            delete.setOnMenuItemClickListener(this);
        }

        @Override
        public boolean onMenuItemClick(MenuItem item) {
            if (mListener!=null){
                //Get the position of the clicked item
                int position = getAdapterPosition();
                if (position!=RecyclerView.NO_POSITION){
                    switch (item.getItemId()){
                        case 1:
                            mListener.onVerifyClick(position);
                            return true;
                        case 2:
                            mListener.onDeleteClick(position);
                            return true;
                    }
                }
            }
            return false;
        }
    }
    public interface OnItemClickListener{
        void onItemClick(int position);

        void onVerifyClick(int position);

        void onDeleteClick(int position);
    }

    public void setOnItemClickListener(OnItemClickListener listener){
        mListener = listener;
    }

    public void setSearchOperation(List<Product> newList){
        mUploads = new ArrayList<>();
        mUploads.addAll(newList);
        notifyDataSetChanged();
    }
}