package com.MarioUceda.easycom.fragments

import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.LinearLayoutManager
import com.MarioUceda.easycom.classes.Precio
import com.MarioUceda.easycom.classes.Producto
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.databinding.FragmentHistBinding

class HistFragment : Fragment() {
    private var _binding: FragmentHistBinding? = null
    private val binding get() = _binding!!
    private lateinit var productos: ArrayList<Producto>
    private lateinit var precios: ArrayList<Precio>
    private lateinit var productAdapter: ProductListAdapter
    private var selectedProductIndex: Int = -1

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentHistBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)



        val layoutManager = LinearLayoutManager(context)
        productAdapter = ProductListAdapter(productos) { position ->
            selectedProductIndex = position
            onItemClick()
        }


        // Set up RecyclerView

        binding.productRecyclerView.layoutManager = layoutManager
        binding.productRecyclerView.adapter = productAdapter
    }

    private fun onItemClick() {
        if (selectedProductIndex >= 0 && selectedProductIndex < productos.size) {
            val selectedProduct = productos[selectedProductIndex]
            cambiarFragmento(selectedProductIndex)

        }
    }

    private fun cambiarFragmento(seleccionado: Int) {
        var prodFragment = ProdFragment()
        val bundle = Bundle().apply {
            putSerializable("producto", productos[seleccionado])
            putSerializable("precio", precios[seleccionado] )
        }
        prodFragment.arguments = bundle

        //cambiar al fragmento de producto
        requireActivity().supportFragmentManager.beginTransaction().apply {
            replace(R.id.containerView, prodFragment)
            commit()
        }
    }

}