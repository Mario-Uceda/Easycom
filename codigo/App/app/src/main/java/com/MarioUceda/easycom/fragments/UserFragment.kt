package com.MarioUceda.easycom.fragments

import android.os.Bundle
import androidx.fragment.app.Fragment
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import com.MarioUceda.easycom.MainActivity
import com.MarioUceda.easycom.Peticiones
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.databinding.FragmentUserBinding


class UserFragment : Fragment() {
    private var _binding: FragmentUserBinding? = null
    private val binding get() = _binding!!
    private lateinit var  peticiones : Peticiones

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentUserBinding.inflate(inflater, container, false)
        return binding.root
    }
    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.btnLogout.setOnClickListener {
            peticiones = Peticiones(requireContext())
            peticiones.cerrarSesion()
            Toast.makeText(context, getString(R.string.toast_exito_logout), Toast.LENGTH_SHORT).show()
            (activity as MainActivity).cambiarScanFragment()
        }
    }

}