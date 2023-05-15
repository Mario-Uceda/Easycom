package com.MarioUceda.easycom.fragments

import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.recyclerview.widget.LinearLayoutManager
import com.MarioUceda.easycom.Peticiones
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.classes.Historial
import com.MarioUceda.easycom.classes.Notificacion
import com.MarioUceda.easycom.classes.Precio
import com.MarioUceda.easycom.classes.Producto
import com.MarioUceda.easycom.databinding.FragmentNotiBinding

class NotiFragment : Fragment() {
    private var _binding: FragmentNotiBinding? = null
    private val binding get() = _binding!!
    private var historial = ArrayList<Historial>()
    private var productos = ArrayList<Producto>()
    private var precios = ArrayList<ArrayList<Precio>>()
    private var notificaciones = ArrayList<Notificacion>()
    private lateinit var notiAdapter: NotiListAdapter
    private var selectedProductIndex: Int = -1
    private lateinit var peticiones : Peticiones


    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentNotiBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.progressBar.visibility = View.VISIBLE
        peticiones = Peticiones(context)
        try{
            peticiones.getNotificaciones() { respuesta ->
                if (respuesta?.historials != null && respuesta?.prices != null && respuesta?.products != null && respuesta?.notificaciones != null) {
                    historial = respuesta.historials as ArrayList<Historial>
                    productos = respuesta.products as ArrayList<Producto>
                    precios = respuesta.prices as ArrayList<ArrayList<Precio>>
                    notificaciones = respuesta.notificaciones as ArrayList<Notificacion>

                    val layoutManager = LinearLayoutManager(context)
                    notiAdapter = NotiListAdapter(productos, notificaciones) { position ->
                        selectedProductIndex = position
                        onItemClick()
                    }
                    // Set up RecyclerView
                    binding.productRecyclerView.layoutManager = layoutManager
                    binding.productRecyclerView.adapter = notiAdapter
                    binding.progressBar.visibility = View.GONE
                } else {
                    Toast.makeText(context,getString(R.string.toast_error_historial), Toast.LENGTH_SHORT).show()
                }
            }
        }catch (e: Exception){
            Toast.makeText(context,getString(R.string.toast_error_historial), Toast.LENGTH_SHORT).show()
        }
    }

    private fun onItemClick() {
        if (selectedProductIndex >= 0 && selectedProductIndex < productos.size) {
            cambiarFragmento(selectedProductIndex)
        }
    }

    private fun cambiarFragmento(seleccionado: Int) {
        var prodFragment = ProdFragment()
        val bundle = Bundle().apply {
            putSerializable("producto", productos[seleccionado])
            putSerializable("precio", precios[seleccionado])
            putSerializable("favorito", historial[seleccionado])
        }
        prodFragment.arguments = bundle

        //cambiar al fragmento de producto
        requireActivity().supportFragmentManager.beginTransaction().apply {
            replace(R.id.containerView, prodFragment)
            commit()
        }
    }
}