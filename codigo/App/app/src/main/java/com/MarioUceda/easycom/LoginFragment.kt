package com.MarioUceda.easycom

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.MarioUceda.easycom.databinding.FragmentLoginBinding
import kotlinx.coroutines.runBlocking

class LoginFragment : Fragment() {

    private var _binding: FragmentLoginBinding? = null
    private val binding get() = _binding!!

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
                val http = Peticiones()
                var logged = runBlocking { http.iniciarSesion( email, password).await() }
                when {
                    logged == 0 -> {
                        Toast.makeText(context, getString(R.string.toast_fallo_login), Toast.LENGTH_SHORT).show()
                    }
                    logged > 0 -> {
                        Toast.makeText(context, getString(R.string.toast_exito_login), Toast.LENGTH_SHORT).show()
                    }
                    else -> {
                        Toast.makeText(context, getString(R.string.toast_error_registro), Toast.LENGTH_SHORT).show()
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
    }

}
