package com.MarioUceda.easycom

import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.MarioUceda.easycom.databinding.FragmentProdBinding
import com.bumptech.glide.Glide


class ProdFragment : Fragment() {
    private var _binding: FragmentProdBinding? = null
    private val binding get() = _binding!!

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentProdBinding.inflate(inflater, container, false)
        return binding.root
    }
    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        val producto = arguments?.getSerializable("producto") as Product
        val precio = arguments?.getSerializable("precio") as Price
        binding.titulo.text = producto.name
        Glide.with(this).load(producto.img).into(binding.prodImg)
        binding.descripcion.text = producto.description
        binding.especificaciones.text = producto.technicalSpecs
        binding.id.text = producto.id
        binding.precio.text = precio.precio.toString()
    }

}