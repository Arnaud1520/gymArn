import { flushPromises, shallowMount } from '@vue/test-utils'
import axios from 'axios'
import { beforeEach, describe, expect, it, vi } from 'vitest'
import { createMemoryHistory, createRouter } from 'vue-router'
import Exercices from '../Exercice.vue'



// Mock d'Axios
vi.mock('axios')

const mockRouter = createRouter({
  history: createMemoryHistory(),
  routes: [{ path: '/exercice/:id', name: 'ExerciceDetail' }]
})

describe('Exercices.vue', () => {
  let wrapper

  beforeEach(async () => {
    axios.get.mockResolvedValueOnce({
      data: {
        member: [
          {
            id: 1,
            name: 'Développé couché',
            categorie: 'Pecs',
            description: 'Travail des pectoraux.'
          },
          {
            id: 2,
            name: 'Squat',
            categorie: 'Jambes',
            description: 'Travail des jambes.'
          }
        ]
      }
    })

    wrapper = shallowMount(Exercices, {
      global: {
        plugins: [mockRouter]
      }
    })

    await flushPromises()
  })

  it('affiche le titre', () => {
    expect(wrapper.find('.title').text()).toBe('Liste des exercices')
  })

  it('charge et affiche les exercices', () => {
    const rows = wrapper.findAll('tbody tr')
    expect(rows.length).toBe(2)
    expect(rows[0].text()).toContain('Développé couché')
    expect(rows[1].text()).toContain('Squat')
  })

  it('filtre les exercices avec la barre de recherche', async () => {
    await wrapper.find('input.search-bar').setValue('squat')
    await flushPromises()
    const visibleRows = wrapper.findAll('tbody tr')
    expect(visibleRows.length).toBe(1)
    expect(visibleRows[0].text()).toContain('Squat')
  })

  it('toggle le formulaire d\'ajout', async () => {
    expect(wrapper.find('.add-form').exists()).toBe(false)
    await wrapper.find('button.add-btn').trigger('click')
    expect(wrapper.find('.add-form').exists()).toBe(true)
  })

  it('n\'ajoute pas l\'exercice si un champ est vide', async () => {
    window.alert = vi.fn()
    await wrapper.find('button.add-btn').trigger('click') // ouvrir le form
    await wrapper.find('button.submit-btn').trigger('click') // tenter d'ajouter

    expect(window.alert).toHaveBeenCalledWith('Veuillez remplir tous les champs et sélectionner une image.')
  })

  it('redirige vers les détails de l\'exercice au clic', async () => {
    const push = vi.spyOn(wrapper.vm.$router, 'push')
    await wrapper.vm.voirDetails(1)
    expect(push).toHaveBeenCalledWith('/exercice/1')
  })
})
