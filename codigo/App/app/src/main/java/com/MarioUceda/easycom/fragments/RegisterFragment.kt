package com.MarioUceda.easycom.fragments

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.MarioUceda.easycom.BBDD
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.databinding.FragmentRegisterBinding
import kotlinx.coroutines.*

class RegisterFragment : Fragment() {

    private var _binding: FragmentRegisterBinding? = null
    private val binding get() = _binding!!

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentRegisterBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        binding.btnRegistrar.setOnClickListener {
            val email = binding.etEmail.text.toString()
            val username = binding.etUsername.text.toString()
            val password = binding.etPassword.text.toString()
            val password2 = binding.etPassword2.text.toString()
            val emailregex = Regex("^[A-Za-z0-9+_.-]+@(.+)$")
            val userregex = Regex("[A-Za-z0-9]+")
            val sqlinjection = Regex("^[a-zA-Z0-9\\s.,@'\\\"-]*$")
            if (email.isEmpty() || username.isEmpty() || password.isEmpty() || password2.isEmpty()) { // Comprobar que los campos no estén vacíos
                Toast.makeText(context, getString(R.string.toast_error_campos), Toast.LENGTH_SHORT).show()
            } else if (!userregex.matches(username)) { // Comprobar que el nombre de usuario sea correcto
                Toast.makeText(context, getString(R.string.toast_error_username), Toast.LENGTH_SHORT).show()
            } else if (!emailregex.matches(email)) {// Comprobar que el email sea correcto
                Toast.makeText(context, getString(R.string.toast_error_email), Toast.LENGTH_SHORT).show()
            } else if (password.length < 4) { // Comprobar que la contraseña tenga más de 4 caracteres
                Toast.makeText(context, getString(R.string.toast_error_contraseña), Toast.LENGTH_SHORT).show()
            } else if (password != password2) { // Comprobar que las contraseñas coincidan
                Toast.makeText(context, getString(R.string.toast_error_contraseñas), Toast.LENGTH_SHORT).show()
            } else if (!sqlinjection.matches(email) ||!sqlinjection.matches(username) || !sqlinjection.matches(password) || !sqlinjection.matches(password2)) { // Comprobar que no se haya introducido código SQL
                Toast.makeText(context, getString(R.string.toast_error_sql), Toast.LENGTH_SHORT).show()
            } else {
                val bbdd = BBDD()
                var registrado = runBlocking { bbdd.registrarUsuario( email, username, password).await() }
                println("principal: "+registrado.toString())
                if (registrado) {
                    Toast.makeText(context, getString(R.string.toast_exito_registro), Toast.LENGTH_SHORT).show()
                    requireActivity().supportFragmentManager.beginTransaction().apply {
                        replace(R.id.containerView, LoginFragment())
                        commit()
                    }
                } else {
                    Toast.makeText(context, getString(R.string.toast_error_registro), Toast.LENGTH_SHORT).show()
                }
            }

            binding.btnLogin.setOnClickListener {
                requireActivity().supportFragmentManager.beginTransaction().apply {
                    replace(R.id.containerView, LoginFragment())
                    commit()
                }
            }

        }
    }
}
