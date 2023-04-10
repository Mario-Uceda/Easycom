package com.MarioUceda.easycom.fragments


import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.Fragment
import com.MarioUceda.easycom.classes.Precio
import com.MarioUceda.easycom.classes.Producto
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.databinding.FragmentScanBinding
import com.google.zxing.integration.android.IntentIntegrator
import com.google.zxing.integration.android.IntentResult

class ScanFragment : Fragment() {
    private var _binding: FragmentScanBinding? = null
    private val binding get() = _binding!!
    //private var producto = Producto("","")
    private var barcode = ""
    private var precio : Precio? = null
    private var prodFragment = ProdFragment()
    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentScanBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        binding.scan.setOnClickListener {
            initScanner()
        }
    }

    //Funcion para iniciar el escaner
    private fun initScanner() {
        // Crear un IntentIntegrator para iniciar la cámara y escanear el código de barras
        val integrator = IntentIntegrator.forSupportFragment(this)
        integrator.setPrompt(resources.getString(R.string.msg_scn))
        integrator.setTorchEnabled(false)
        integrator.setBeepEnabled(true)
        integrator.initiateScan()
    }

    //Función para obtener el codigo obtenido del escaner y abrir el fragmento de producto
    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        // Obtener el resultado del escaneo de código de barras
        val result: IntentResult? = IntentIntegrator.parseActivityResult(requestCode, resultCode, data)

        // Verificar si se obtuvo un resultado
        if (result != null) {
            // Verificar si el escaneo fue exitoso
            if (result.contents != null) {
                // Obtener el código de barras escaneado
                barcode = "${result.contents.toString()}"
                /*val bundle = Bundle().apply {
                    putSerializable("producto", producto)
                    putSerializable("precio", precio)
                }
                prodFragment.arguments = bundle

                //cambiar al fragmento de producto
                requireActivity().supportFragmentManager.beginTransaction().apply {
                    replace(R.id.containerView, prodFragment)
                    commit()
                }*/
            }
        }
    }

}