package com.MarioUceda.easycom.fragments


import android.content.Intent
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import com.MarioUceda.easycom.Peticiones
import com.MarioUceda.easycom.classes.Precio
import com.MarioUceda.easycom.R
import com.MarioUceda.easycom.classes.Producto
import com.MarioUceda.easycom.databinding.FragmentScanBinding
import com.google.zxing.integration.android.IntentIntegrator
import com.google.zxing.integration.android.IntentResult

class ScanFragment : Fragment() {
    private var _binding: FragmentScanBinding? = null
    private val binding get() = _binding!!

    private lateinit var producto : Producto
    private lateinit var barcode : String
    private lateinit var  peticiones : Peticiones
    private lateinit var precio : Precio

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
        peticiones = Peticiones(context)
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

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        val result: IntentResult? = IntentIntegrator.parseActivityResult(requestCode, resultCode, data)
        if (result != null) {
            if (result.contents != null) {
                barcode = "${result.contents.toString()}"

                try {
                    peticiones.buscarProducto(barcode, "") { respuesta ->
                        if (respuesta.status == "ok" && respuesta.price != null && respuesta.product != null) {
                            precio = respuesta.price
                            producto = respuesta.product
                            verProducto()
                        } else {
                            Toast.makeText(context,getString(R.string.toast_error_producto),Toast.LENGTH_SHORT).show()
                        }
                    }
                }catch (e: Exception) {
                    Toast.makeText(context,getString(R.string.toast_error_producto),Toast.LENGTH_SHORT).show()
                }
            }
        }
    }

    fun verProducto() {
        val bundle = Bundle().apply {
            putSerializable("producto", producto)
            putSerializable("precio", precio)
        }
        prodFragment.arguments = bundle

        //cambiar al fragmento de producto
        requireActivity().supportFragmentManager.beginTransaction().apply {
            replace(R.id.containerView, prodFragment)
            commit()
        }
    }

}