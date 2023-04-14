package com.MarioUceda.easycom.fragments

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.MarioUceda.easycom.MainActivity
import com.MarioUceda.easycom.Peticiones
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.databinding.FragmentLoginBinding

class LoginFragment : Fragment() {

    private var _binding: FragmentLoginBinding? = null
    private val binding get() = _binding!!
    private lateinit var  peticiones : Peticiones

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentLoginBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.btnLogin.setOnClickListener {
            println("Email: ${binding.etEmail.text}")
            println("Contraseña: ${binding.etPassword.text}")
            val email = binding.etEmail.text.toString()
            val password = binding.etPassword.text.toString()
            val emailregex = Regex("^[A-Za-z0-9+_.-]+@(.+)$")
            if (email.isEmpty() || password.isEmpty()) { // Comprobar que los campos no estén vacíos

                Toast.makeText(context, getString(R.string.toast_error_campos), Toast.LENGTH_SHORT).show()
            } else if (!emailregex.matches(email)) {// Comprobar que el email sea correcto
                Toast.makeText(context, getString(R.string.toast_error_email), Toast.LENGTH_SHORT).show()
            } else {
                peticiones= Peticiones(context)
                peticiones.iniciarSesion(email, password) { respuesta ->
                    Toast.makeText(context, respuesta.message, Toast.LENGTH_SHORT).show()
                    if (respuesta.status == "ok") {
                        (activity as MainActivity).cambiarScanFragment()
                    }
                }
            }
        }

        binding.btnRegistrar.setOnClickListener {
            requireActivity().supportFragmentManager.beginTransaction().apply {
                replace(R.id.containerView, RegisterFragment())
                commit()
            }
        }
        binding.btnAnonimo.setOnClickListener {
            (activity as MainActivity).cambiarScanFragment()
        }
    }

}
