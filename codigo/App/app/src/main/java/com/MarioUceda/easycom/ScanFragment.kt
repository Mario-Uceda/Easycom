package com.MarioUceda.easycom

import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import com.MarioUceda.easycom.databinding.FragmentScanBinding
import com.google.zxing.integration.android.IntentIntegrator
import com.google.zxing.integration.android.IntentResult


class ScanFragment : Fragment() {
    private var _binding: FragmentScanBinding? = null
    private val binding get() = _binding!!
    private var mensaje = ""
    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentScanBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.texto.text = mensaje
        binding.scan.setOnClickListener { initScanner() }
    }

    private fun initScanner() {
        // Crear un IntentIntegrator para iniciar la cámara y escanear el código de barras
        val integrator = IntentIntegrator.forSupportFragment(this)
        integrator.setPrompt(resources.getString(R.string.msg_scn))
        integrator.setTorchEnabled(false)
        integrator.setBeepEnabled(true)
        integrator.initiateScan()
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        // Obtener el resultado del escaneo de código de barras
        val result: IntentResult? = IntentIntegrator.parseActivityResult(requestCode, resultCode, data)

        // Verificar si se obtuvo un resultado
        if (result != null) {
            // Verificar si el escaneo fue exitoso
            if (result.contents != null) {
                // Obtener el código de barras escaneado
                mensaje = "${result.contents.toString()}"
                binding.texto.text = mensaje
            } else {
                mensaje = "el escaneo ha fallado"
                binding.texto.text = mensaje
            }
        }
    }

}