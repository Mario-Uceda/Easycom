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
import org.jsoup.Jsoup
import kotlin.concurrent.thread


class ScanFragment : Fragment() {
    private var _binding: FragmentScanBinding? = null
    private val binding get() = _binding!!
    private var producto = Product("","")

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? {
        _binding = FragmentScanBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.scan.setOnClickListener { initScanner() }
        binding.buscar.setOnClickListener {
            if (producto.id != ""){
                thread {
                    producto.url = getProductNameFromAmazon()
                    if (producto.url != ""){
                        producto.name = productAmazon(producto.url)
                    }
                    println("La url es: "+producto.url)
                    println("El nombre del producto es: "+producto.name)
                    requireActivity().runOnUiThread {
                        binding.url.text= getString(R.string.texto_url)+ producto.url
                        binding.productName.text= getString(R.string.texto_nombre)+ producto.name
                    }
                }/*'6972453163820'*/
                println("1La url es: "+producto.url)
                println("1El nombre del producto es: "+producto.name)
                //binding.url.text= getString(R.string.texto_url)+ producto.url
                //binding.productName.text= getString(R.string.texto_nombre)+ producto.name
            }
        }
        /*binding.buscar.setOnClickListener {
            obtenerProductoEnHilo(producto) { url, name ->
                if (url != producto.url) {
                    producto.url = url
                    producto.name = name
                    println("La url es: ${producto.url}")
                    println("El nombre del producto es: ${producto.name}")
                    //binding.idProducto.text = getString(R.string.texto_url) + producto.url
                    //binding.idProducto.text = getString(R.string.texto_nombre) + producto.name
                }
            }
        }*/
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
    //Función para obtener el codigo obtenido del escaner
    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        // Obtener el resultado del escaneo de código de barras
        val result: IntentResult? = IntentIntegrator.parseActivityResult(requestCode, resultCode, data)

        // Verificar si se obtuvo un resultado
        if (result != null) {
            // Verificar si el escaneo fue exitoso
            if (result.contents != null) {
                // Obtener el código de barras escaneado
                producto.id = "${result.contents.toString()}"
                binding.idProducto.text= getString(R.string.texto_codigo)+ producto.id
            }
        }
    }

    fun obtenerProductoEnHilo(producto: Product, callback: (String, String) -> Unit) {
        thread {
            val url = getProductNameFromAmazon()
            var name = ""
            if (url != ""){
                name = productAmazon(url)
            }
            callback(url, name)
        }
    }

    //Funcion para buscar el codigo en Amazon y buscar si el producto existe
     fun getProductNameFromAmazon() : String {
        val urlAmazon = "https://www.amazon.es"
        val urlBusqueda = urlAmazon+"/s?k="+producto.id
        try {
            val doc = Jsoup.connect(urlBusqueda).get()
            println(urlBusqueda)
            val urlProduct = doc.select("h2 > a").attr("href")
            println(urlProduct)
            return urlAmazon+ urlProduct
        }catch (e: Exception){
            print(e.message)
            return ""
        }
    }
    //Funcion para obtener datos de un producto de Amazon
    fun productAmazon(url: String): String {
        try {
            println("Holiwi")
            val doc = Jsoup.connect(url).get()
            println(url)
            val nombreProducto = doc.select("#productTitle").text()
            println("UwU")
            println(nombreProducto)
            return nombreProducto
        }catch (e: Exception){
            print(e.message)
            return ""
        }
    }


}