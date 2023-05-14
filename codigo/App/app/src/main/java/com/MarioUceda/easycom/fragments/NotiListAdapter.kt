package com.MarioUceda.easycom.fragments

import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.MarioUceda.easycom.classes.Notificacion
import com.MarioUceda.easycom.classes.Producto
import com.MarioUceda.easycom.databinding.ItemNotiBinding
import com.bumptech.glide.Glide
import java.time.LocalDateTime
import java.time.format.DateTimeFormatter
import java.time.temporal.ChronoUnit

class NotiListAdapter(private val products: List<Producto>,private val notificaciones: List<Notificacion>, private val onItemClickListener: (Int) -> Unit) :
    RecyclerView.Adapter<NotiListAdapter.ViewHolder>() {

    inner class ViewHolder(private val binding: ItemNotiBinding) :
        RecyclerView.ViewHolder(binding.root) {
        fun bind(producto: Producto, notificacion: Notificacion) {
            binding.apply {
                productName.text = producto.name
                fechaNotificacion.text = getPriceTime(notificacion.updated_at)
                precioActual.text = notificacion.precio_actual.toString()
                precioMinimo.text = notificacion.precio_minimo.toString()
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
        val binding = ItemNotiBinding.inflate(LayoutInflater.from(parent.context), parent, false)
        return ViewHolder(binding)
    }

    fun getPriceTime(dateString: String): String {
        val date = LocalDateTime.parse(dateString, DateTimeFormatter.ISO_DATE_TIME)
        val datePlusTwoHours = date.plus(2, ChronoUnit.HOURS)
        val formatter = DateTimeFormatter.ofPattern("dd/MM/yyyy (HH:mm'h')")
        return datePlusTwoHours.format(formatter)
    }

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(products[position],notificaciones[position])
    }

    override fun getItemCount(): Int = products.size
}
