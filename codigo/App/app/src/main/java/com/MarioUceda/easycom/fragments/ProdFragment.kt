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
    private lateinit var precios: ArrayList<Precio>
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
        precios = arguments?.getSerializable("precio") as ArrayList<Precio>


        binding.titulo.text = producto.name
        Glide.with(this).load(producto.img).into(binding.prodImg)
        binding.descripcion.text = producto.description
        binding.especificaciones.text = producto.especificaciones
        binding.precioAmazon.text = "--€"
        binding.fechaAmazon.text = "--"
        binding.precioMediamarkt.text = "--€"
        binding.fechaMediamarkt.text = "--"
        binding.precioEbay.text = "--€"
        binding.fechaEbay.text = "--"
        for(p in precios){
            if(p.tienda == "Amazon"){
                binding.precioAmazon.text = p.precio.toString()+"€"
                binding.fechaAmazon.text = getPriceTime(p.created_at)
                binding.amazon.setOnClickListener { abrirPagina(p.urlProducto) }
            }else if (p.tienda == "Mediamarkt"){
                binding.precioMediamarkt.text = p.precio.toString()+"€"
                binding.fechaMediamarkt.text = getPriceTime(p.created_at)
                binding.mediamarkt.setOnClickListener { abrirPagina(p.urlProducto) }
            }else if (p.tienda == "Ebay"){
                binding.precioEbay.text = p.precio.toString()+"€"
                binding.fechaEbay.text = getPriceTime(p.created_at)
                binding.ebay.setOnClickListener { abrirPagina(p.urlProducto) }
            }
        }
        var historial = arguments?.getSerializable("favorito")
        if (historial == null) {
            binding.fav.visibility = View.GONE
        } else {
            favorito = arguments?.getSerializable("favorito") as Historial
            binding.fav.isChecked = favorito!!.favorito == 1;
            binding.fav.setOnClickListener { checkbox() }
        }

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
        peticiones.cambiarFavorito(favorito!!){ respuesta ->
            if (respuesta.historial == null) {
                Toast.makeText(context,getString(R.string.toast_error_favoritos), Toast.LENGTH_SHORT).show()
            }
        }
    }
}