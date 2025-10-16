import axios from 'axios';

const API_BASE = '/vehicles';

export default {
  list() {
    return axios.get(`${API_BASE}/list`);
  },
  save(vehicle) {
    return axios.post(API_BASE, vehicle);
  },
  save(vehicle) {
    return axios.put(`${API_BASE}/${vehicle.id}`, vehicle);
  },
  delete(id) {
    return axios.delete(`${API_BASE}/${id}`);
  }
}