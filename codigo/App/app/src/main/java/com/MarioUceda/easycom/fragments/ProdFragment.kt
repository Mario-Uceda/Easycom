package com.MarioUceda.easycom.fragments

import android.content.Intent
import android.net.Uri
import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import com.MarioUceda.easycom.Peticiones
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.classes.Historial
import com.MarioUceda.easycom.classes.Precio
import com.MarioUceda.easycom.classes.Producto
import com.MarioUceda.easycom.databinding.FragmentProdBinding
import com.bumptech.glide.Glide
import java.time.LocalDateTime
import java.time.format.DateTimeFormatter
import java.time.temporal.ChronoUnit


class ProdFragment : Fragment() {
    private var _binding: FragmentProdBinding? = null
    private val binding get() = _binding!!
    private lateinit var precio: Precio
    private lateinit var producto: Producto
    private lateinit var favorito: Historial
    private lateinit var peticiones : Peticiones

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentProdBinding.inflate(inflater, container, false)
        return binding.root
    }
    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        peticiones = Peticiones(context)
        producto = arguments?.getSerializable("producto") as Producto
        precio = arguments?.getSerializable("precio") as Precio
        favorito = arguments?.getSerializable("favorito") as Historial

        binding.titulo.text = producto.name
        Glide.with(this).load(producto.img).into(binding.prodImg)
        binding.descripcion.text = producto.description
        binding.especificaciones.text = producto.especificaciones
        binding.id.text = producto.id
        binding.precioAmazon.text = precio.precio.toString()+"€"
        binding.fechaAmazon.text = getPriceTime(precio.created_at)
        binding.fav.isChecked = favorito.favorito == 1;

        binding.fav.setOnClickListener { checkbox() }
        binding.amazon.setOnClickListener { abrirPagina(precio.urlProducto) }
    }

    fun getPriceTime(dateString: String): String {
        val date = LocalDateTime.parse(dateString, DateTimeFormatter.ISO_DATE_TIME)
        val datePlusTwoHours = date.plus(2, ChronoUnit.HOURS)
        val formatter = DateTimeFormatter.ofPattern("dd/MM/yyyy (HH:mm'h')")
        return datePlusTwoHours.format(formatter)
    }
    fun abrirPagina(url: String) {
        val intent = Intent(Intent.ACTION_VIEW)
        intent.data = Uri.parse(url)
        startActivity(intent)
    }
    fun checkbox (){
        peticiones.cambiarFavorito(favorito){ respuesta ->
            if (respuesta.historial == null) {
                Toast.makeText(context,getString(R.string.toast_error_favoritos), Toast.LENGTH_SHORT).show()
            }
        }
    }
}