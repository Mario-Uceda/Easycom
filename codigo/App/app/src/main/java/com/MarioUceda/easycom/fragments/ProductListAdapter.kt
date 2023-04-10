package com.MarioUceda.easycom.fragments

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.MarioUceda.easycom.classes.Producto
import com.MarioUceda.easycom.databinding.ItemProductBinding
import com.bumptech.glide.Glide

class ProductListAdapter(private val products: List<Producto>, private val onItemClickListener: (Int) -> Unit) :
    RecyclerView.Adapter<ProductListAdapter.ViewHolder>() {

    inner class ViewHolder(private val binding: ItemProductBinding) :
        RecyclerView.ViewHolder(binding.root) {
        fun bind(producto: Producto) {
            binding.apply {
                productName.text = producto.name
                productDescription.text = producto.description
                // Cargar la imagen utilizando Glide
                Glide.with(binding.root)
                    .load(producto.img)
                    .into(binding.productImage)
            }
            itemView.setOnClickListener {
                onItemClickListener(adapterPosition)
            }
        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val binding =
            ItemProductBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(products[position])
    }

    override fun getItemCount(): Int = products.size
}
