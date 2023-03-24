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
import kotlinx.coroutines.*
import org.jsoup.Jsoup

class ScanFragment : Fragment() {
    private var _binding: FragmentScanBinding? = null
    private val binding get() = _binding!!
    private var producto = Product("","")
    private var precio : Price? = null
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
                producto.id = "${result.contents.toString()}"
                getAmazon()
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
    }

    //Funcion para obtener la información del producto
    private fun getAmazon() {
        producto.url = runBlocking { getProductAmazonAsync().await() }
        if (producto.url != "") {
            val lista = runBlocking { getProductDataAmazonAsync(producto.url).await() }
            producto.name = lista[0]
            producto.img = lista[1]
            producto.description = lista[2]
            producto.technicalSpecs = lista[3]
            precio = Price("Amazon", producto.id , lista[4])
            println(producto.toString())
        }

    }

    //Funcion para buscar el codigo en Amazon y buscar si el producto existe
    fun getProductAmazonAsync(): Deferred<String> = CoroutineScope(Dispatchers.IO).async {
        val urlAmazon = "https://www.amazon.es/"
        val urlBusqueda = urlAmazon + "s?k=" + producto.id
        try {
            val doc = Jsoup.connect(urlBusqueda).get()
            val urlProduct = doc.select("h2 > a").attr("href")
            urlAmazon + urlProduct
        } catch (e: Exception) {
            print(e.message)
            ""
        }
    }

    fun getProductDataAmazonAsync(url: String): Deferred<Array<String>> = CoroutineScope(Dispatchers.IO).async {
        try {
            val doc = Jsoup.connect(url).get()
            //Nombre del producto
            val nombreProducto = doc.select("#productTitle").text()
            //Imagen del producto
            val img = doc.select("#imgTagWrapperId img").attr("src")
            //Descripción del producto
            val descriptor = doc.select("#feature-bullets > ul > li").text()
            //Especificaciones del producto
            var specs = ""
            try {
                val table= doc.select("#productDetails_techSpec_section_1")
                val rows = table.select("tr")
                // Recorre las filas y obtiene el texto de las celdas
                for (row in rows) {
                    // Obtiene las celdas de la fila
                    val cells = row.select("td, th")

                    // Obtiene el texto de la primera y segunda celdas
                    val atributo = cells[0].text()
                    val valor= cells[1].text()
                    println(atributo + ": " + valor)
                    // guardo la información obtenida
                    specs += atributo + ": " + valor+"\n"
                }
            } catch (e: Exception) {
                specs = "La tabla no existe en la página"
            }
            //Precio del producto
            val decimal = doc.select(".a-price-whole").text().split(",")[0]
            val fraccion = doc.select(".a-price-fraction").text().split(" ")[0]
            val precio = (decimal + "." + fraccion + "€")
            arrayOf(nombreProducto, img, descriptor, specs, precio)
        } catch (e: Exception) {
            print(e.message)
            arrayOf("nombreProducto", "img", "descriptor", "specs", "precio")
        }
    }





}