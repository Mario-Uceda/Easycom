package com.MarioUceda.easycom

import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import androidx.fragment.app.Fragment
import com.MarioUceda.easycom.classes.SharedPreferences
import com.MarioUceda.easycom.databinding.ActivityMainBinding
import com.MarioUceda.easycom.fragments.*
import com.google.android.material.bottomnavigation.BottomNavigationView

class MainActivity : AppCompatActivity() {

    private lateinit var binding: ActivityMainBinding
    private lateinit var  sharedPref : SharedPreferences

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityMainBinding.inflate(layoutInflater)
        setContentView(binding.root)

        sharedPref = SharedPreferences(this)

        val fragments = mapOf(
            R.id.nav_scaner to ScanFragment(),
            R.id.nav_favoritos to if (sharedPref.isUserLogged()) FavFragment() else LoginFragment(),
            R.id.nav_historial to if (sharedPref.isUserLogged()) HistFragment() else LoginFragment(),
            R.id.nav_notificaciones to if (sharedPref.isUserLogged()) NotiFragment() else LoginFragment(),
            R.id.nav_usuario to if (sharedPref.isUserLogged()) UserFragment() else LoginFragment()
        )

        val navListener = BottomNavigationView.OnNavigationItemSelectedListener { menuItem ->
            val fragment = fragments[menuItem.itemId]
            fragment?.let {
                setCurrentFragment(it)
                return@OnNavigationItemSelectedListener true
            }
            return@OnNavigationItemSelectedListener false
        }

        binding.bottomNavigationView.setOnNavigationItemSelectedListener(navListener)
        binding.bottomNavigationView.selectedItemId = R.id.nav_scaner

        //alertsIcon(10)
    }


    private fun alertsIcon(numero: Int) {
        if (numero > 0) {
            binding.bottomNavigationView.getOrCreateBadge(R.id.nav_notificaciones).apply {
                isVisible = true
                number = numero
            }
        } else {
            binding.bottomNavigationView.getOrCreateBadge(R.id.nav_notificaciones).apply {
                isVisible = false
            }
        }
    }

    private fun setCurrentFragment(fragment: Fragment) {
        supportFragmentManager.beginTransaction().apply {
            replace(R.id.containerView, fragment)
            commit()
        }
    }

    fun cambiarScanFragment() {
        recreate()
        val scanFragment = ScanFragment()
        setCurrentFragment(scanFragment)
        binding.bottomNavigationView.selectedItemId = R.id.nav_scaner
    }

}