package com.MarioUceda.easycom

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import androidx.fragment.app.Fragment
import com.MarioUceda.easycom.databinding.ActivityMainBinding
import com.MarioUceda.easycom.fragments.*

class MainActivity : AppCompatActivity() {

    private lateinit var binding: ActivityMainBinding
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        var favFragment = FavFragment()
        var histFragment = HistFragment()
        var notiFragment = NotiFragment()
        var scanFragment = ScanFragment()
        var userFragment = UserFragment()
        var loginFragment = LoginFragment()
        var productListFragment = ProductListFragment()

        binding.bottomNavigationView.setOnNavigationItemSelectedListener {
            when (it.itemId) {
                R.id.nav_favoritos -> {
                    setCurrentFragment(favFragment)
                    true
                }

                R.id.nav_historial -> {
                    setCurrentFragment(histFragment)
                    true
                }

                R.id.nav_scaner -> {
                    setCurrentFragment(scanFragment)
                    true
                }

                R.id.nav_notificaciones -> {
                    setCurrentFragment(notiFragment)
                    alertsIcon(0)
                    true
                }

                R.id.nav_usuario -> {
                    setCurrentFragment(loginFragment)
                    true
                }

                else -> false
            }
        }

        alertsIcon(10)

    }

    private fun alertsIcon(numero:Int) {
        if (numero > 0){
            binding.bottomNavigationView.getOrCreateBadge(R.id.nav_notificaciones).apply {
                isVisible = true
                number = numero
            }
        }else{
            binding.bottomNavigationView.getOrCreateBadge(R.id.nav_notificaciones).apply {
                isVisible = false
            }
        }
    }

    private fun setCurrentFragment(fragment: Fragment){
        supportFragmentManager.beginTransaction().apply {
            replace(R.id.containerView, fragment)
            commit()
        }
    }

    //TODO: shared preferences
    /*
    val sharedPref = context.getSharedPreferences("MyPrefs", Context.MODE_PRIVATE)
    val editor = sharedPref.edit()
    editor.putBoolean("dark_mode", true)
    editor.apply()

    val sharedPref = context.getSharedPreferences("MyPrefs", Context.MODE_PRIVATE)
    val darkMode = sharedPref.getBoolean("dark_mode", false)

    */

}